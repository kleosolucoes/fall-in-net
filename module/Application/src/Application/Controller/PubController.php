<?php

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Application\Form\LoginForm;
use Application\Form\KleoForm;
use Application\Form\AtivoCadastrarSenhaForm;

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

  public function ativoCadastrarSenhaAction() {

    $formulario = $this->params()->fromRoute(self::stringFormulario);
    if ($formulario) {
      $inputToken = $formulario->get(KleoForm::inputId);
      $pessoa = self::getRepositorio()->getPessoaORM()->encontrarPorToken($inputToken->getValue());
      $pessoa->setId($inputToken->getValue());
    } else {
      $token = $this->getEvent()->getRouteMatch()->getParam(self::stringToken);
      $pessoa = self::getRepositorio()->getPessoaORM()->encontrarPorToken($token);
      $pessoa->setId($token);
      $formulario = new AtivoCadastrarSenhaForm('AtivoCadastrarSenha', $pessoa);
    }
    return new ViewModel(
      array(
        self::stringFormulario => $formulario,
        self::stringPessoa => $pessoa,
      ));
  }

  public function ativoCadastrarSenhaFinalizarAction() {
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        self::getRepositorio()->iniciarTransacao();

        $post_data = $request->getPost();
        $token = $post_data[KleoForm::inputId];
        $pessoa = self::getRepositorio()->getPessoaORM()->encontrarPorToken($token);

        $formulario = new AtivoCadastrarSenhaForm(null);
        $formulario->setInputFilter($pessoa->getInputFilterCadastrarSenhaAtivo());

        $formulario->setData($post_data);

        if ($formulario->isValid()) {
           $validatedData = $formulario->getData();
          $pessoa->setSenha($validatedData[KleoForm::inputSenha]);
          $pessoa->setToken(null);

          self::getRepositorio()->getPessoaORM()->persistir($pessoa);
         
          $emails[] = $pessoa->getEmail();
          $titulo = self::traducaoEmailTitulo;
          $mensagem = '';
          $mensagem = '<p>Senha Cadastra com Sucesso</p>';
          $mensagem .= '<p>Usuario: ' . $pessoa->getEmail() . '</p>';
          $mensagem .= '<p>Senha: ' . $post_data[KleoForm::inputSenha] . '</p>';
          $mensagem .= '<p><a href="' . self::url . 'login">Clique aqui acessar</a></p>';
          self::enviarEmail($emails, $titulo, $mensagem);

          self::getRepositorio()->fecharTransacao();         
        } else {
          self::getRepositorio()->desfazerTransacao();
           self::mostrarMensagensDeErroFormulario($formulario->getMessages());
          return $this->forward()->dispatch(self::controllerPub, array(
            self::stringAction => 'ativoCadastrarSenha',
            self::stringFormulario => $formulario,
          ));
        }
      } catch (Exception $exc) {
        self::getRepositorio()->desfazerTransacao();
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
