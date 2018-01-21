<?php

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Application\Model\Entity\Responsavel;
use Application\Model\Entity\ContaCorrente;
use Application\Model\Entity\ContaCorrenteSituacao;
use Application\Model\Entity\ResponsavelSituacao;
use Application\Model\Entity\Situacao;
use Application\Model\ORM\RepositorioORM;
use Application\Form\CadastroResponsavelForm;
use Application\Form\ResponsavelAtualizacaoForm;
use Application\Form\ResponsavelSenhaAtualizacaoForm;
use Application\Form\LoginForm;
use Application\Form\KleoForm;

class PubController extends KleoController {

    private $_doctrineAuthenticationService;

    /**
     * Contrutor sobrecarregado com os serviços de ORM
     */
    public function __construct(EntityManager $doctrineORMEntityManager = null, AuthenticationService $doctrineAuthenticationService = null) {

        if (!is_null($doctrineORMEntityManager)) {
            parent::__construct($doctrineORMEntityManager);
        }
        if (!is_null($doctrineAuthenticationService)) {
            $this->_doctrineAuthenticationService = $doctrineAuthenticationService;
        }
    }

    /**
     * Função padrão, traz a tela principal
     * GET /
     */
    public function indexAction() {

        $this->setLayoutSite();

        return new ViewModel();
    }

    /**
     * Formulario para alterar dados do responsavel
     * GET /cadastroResponsavelSenhaAtualizacao
     */
    public function responsavelSenhaAtualizacaoAction() {

        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $formulario = $this->params()->fromRoute(self::stringFormulario);
        if ($formulario) {
            $responsavelSenhaAtualizacaoForm = $formulario;
            $inputToken = $formulario->get(KleoForm::inputId);
            $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorToken($inputToken->getValue());
            $responsavel->setId($inputToken->getValue());
        } else {
            $token = $this->getEvent()->getRouteMatch()->getParam(self::stringToken);
            $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorToken($token);
            $responsavel->setId($token);
            $responsavelSenhaAtualizacaoForm = new ResponsavelSenhaAtualizacaoForm('ResponsavelSenhaAtualizacao', $responsavel);
        }

        return new ViewModel(
                array(
            self::stringFormulario => $responsavelSenhaAtualizacaoForm,
            KleoForm::inputEmail => $responsavel->getEmail(),
        ));
    }

    /**
     * Atualiza a senha do responsavel
     * GET /cadastroResponsavelSenhaAtualizar
     */
    public function responsavelSenhaAtualizarAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
            try {
                $repositorioORM->iniciarTransacao();

                $post_data = $request->getPost();
                $token = $post_data[KleoForm::inputId];
                $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorToken($token);

                $responsavelSenhaAtualizacaoForm = new ResponsavelSenhaAtualizacaoForm(null, $responsavel);
                $responsavelSenhaAtualizacaoForm->setInputFilter($responsavel->getInputFilterCadastrarSenhaResponsavel());

                $responsavelSenhaAtualizacaoForm->setData($post_data);

                if ($responsavelSenhaAtualizacaoForm->isValid()) {

                    $responsavel->exchangeArray($responsavelSenhaAtualizacaoForm->getData());
                    $responsavel->setToken(null);

                    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
                    $repositorioORM->getResponsavelORM()->persistir($responsavel);

                    /* Colocando 10 creditos */
                    $contaCorrente = new ContaCorrente();
                    $contaCorrente->setResponsavel($responsavel);
                    $contaCorrente->setValor(10);
                    $contaCorrente->setPreco(0);
                    $contaCorrente->setCredito('S');
                    $repositorioORM->getContaCorrenteORM()->persistir($contaCorrente);
                    /* Fim creditos */

                    $emails[] = $responsavel->getEmail();
                    $titulo = self::emailTitulo;
                    $mensagem = '';
                    $mensagem = '<p>Senha Cadastra com Sucesso</p>';
                    $mensagem .= '<p>Usuario: ' . $responsavel->getEmail() . '</p>';
                    $mensagem .= '<p>Senha: ' . $post_data[KleoForm::inputSenha] . '</p>';
                    $mensagem .= '<p><a href="' . self::url . 'login">Clique aqui acessar</a></p>';
                    self::enviarEmail($emails, $titulo, $mensagem);

                    $repositorioORM->fecharTransacao();

                    return $this->redirect()->toRoute(self::rotaPub, array(
                                self::stringAction => 'responsavelSenhaCadastrado',
                    ));
                } else {
                    $repositorioORM->desfazerTransacao();
                    return $this->forward()->dispatch(self::controllerPub, array(
                                self::stringAction => 'responsavelSenhaAtualizacao',
                                self::stringFormulario => $responsavelSenhaAtualizacaoForm,
                    ));
                }
            } catch (Exception $exc) {
                $repositorioORM->desfazerTransacao();
                echo $exc->getMessage();
            }
        }
        return new ViewModel();
    }

    public function loginAction() {

        $formulario = $this->params()->fromRoute(self::stringFormulario);
        if ($formulario) {
            $loginForm = $formulario;
        } else {
            $loginForm = new LoginForm('login');
        }
        $token = $this->getEvent()->getRouteMatch()->getParam(self::stringToken);
        return new ViewModel(
                array(
            self::stringFormulario => $loginForm,
            'error' => $token,
                )
        );
    }

    public function logarAction() {

        $data = $this->getRequest()->getPost();

        $usuarioTrim = trim($data[KleoForm::inputEmail]);
        $senhaTrim = trim($data[KleoForm::inputSenha]);
        $adapter = $this->getDoctrineAuthenticationServicer()->getAdapter();
        $adapter->setIdentityValue($usuarioTrim);
        $adapter->setCredentialValue(md5($senhaTrim));
        $authenticationResult = $this->getDoctrineAuthenticationServicer()->authenticate();

        if ($authenticationResult->isValid()) {
            
            $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($usuarioTrim);
           
            if ($pessoa->verificarSeTemAlgumaResponsabilidadeAtiva()) {
                /* Registro de sessão */
                $sessao = $this->getSessao();
                $sessao->idPessoa = $pessoa->getId();

                return $this->redirect()->toRoute(self::rotaAdm, array(
                            self::stringAction => self::stringIndex,
                ));
            } else {
                return $this->forward()->dispatch(self::controllerPub, array(
                            self::stringAction => self::stringLogin,
                ));
            }
        } else {
            return $this->forward()->dispatch(KleoController::controllerPub, array(
                        self::stringAction => self::stringLogin,
                        self::stringToken => 'Login invalido',
            ));
        }
    }

    /**
     * Recupera autenticação doctrine
     * @return AuthenticationService
     */
    public function getDoctrineAuthenticationServicer() {
        return $this->_doctrineAuthenticationService;
    }

}
