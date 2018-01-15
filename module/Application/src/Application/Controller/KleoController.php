<?php

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use PHPMailer;
use Zend\Session\Container;
use Zend\Json\Json;
use Zend\File\Transfer\Adapter\Http;
use Application\Model\Entity\KleoEntity;
use Application\Form\KleoForm;
use Application\Model\ORM\RepositorioORM;

/**
 * Nome: KleoController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle com propriedade do ORM
 */
class KleoController extends AbstractActionController {

  private $_doctrineORMEntityManager;
  private $sessao;
  private $repositorio;

  const nomeAplicacao = 'AFabricaOficial';
  const nomeAplicacaoDescricao = 'Rotinas + Constância = Sucesso e Saúde';
  const idResponsavelAdmin = 1;
  const stringFormulario = 'formulario';
  const stringAction = 'action';
  const stringId = 'id';
  const stringToken = 'token';
  const stringLogin = 'login';
  const stringResponsavel = 'responsavel';
  const stringResponsaveis = 'responsaveis';
  const stringBot = 'bot';
  const stringBots = 'bots';
  const stringLista = 'lista';
  const stringListas = 'listas';
  const stringCampanha = 'campanha';
  const stringCampanhas = 'campanhas';
  const stringIndex = 'index';
  const stringIdResponsavel = 'responsavel_id';
  const controllerPub = 'Application\Controller\Pub';
  const controllerAdm = 'Application\Controller\Adm';
  const rotaPub = 'pub';
  const rotaAdm = 'adm';
  //  const url = 'http://sender-falecomleonardopereira890682.codeanyapp.com/';
  const url = 'http://zapmarketing.com.br/';
  const stringMensagem = 'mensagem';
  const diretorioDocumentos = '/../../../../public/assets';
  const emailTitulo = 'Zapmarketing';
  const emailLeo = 'falecomleonardopereira@gmail.com';
  const emailKort = 'diegokort@zapmarketing.com.br';
  const emailSilverio = 'comercial@zapmarketing.com.br';

  /**
     * Contrutor sobrecarregado com os serviços de ORM
     */
  public function __construct(EntityManager $doctrineORMEntityManager = null) {

    if (!is_null($doctrineORMEntityManager)) {
      $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
    }
  }

  /**
     * Função para enviar email
     */
  public static function enviarEmail($emails, $titulo, $mensagem) {
    $mail = new PHPMailer;
    try {
      //            $mail->SMTPDebug = 1;
      $mail->isSMTP();
      $mail->Host = '200.147.36.31';
      $mail->SMTPAuth = true;
      $mail->Username = 'informativo@zapmarketing.com.br';
      $mail->Password = '97zmCQUKY3LcsZ96';
      //            $mail->SMTPSecure = 'tls';
      $mail->Port = 587;
      $mail->setFrom('informativo@zapmarketing.com.br', self::nomeAplicacao);

      foreach ($emails as $email) {
        $mail->addAddress($email);
      }

      $mail->isHTML(true);
      $mail->Subject = $titulo;
      $mail->Body = $mensagem;
      //      $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      $mail->send();
    } catch (Exception $exc) {
      echo $mail->ErrorInfo;
      echo $exc->getMessage();
    }
  }

  /**
     * Retorna a sessao inicializada
     */
  public function getSessao() {
    if (!$this->sessao) {
      $this->sessao = new Container(self::nomeAplicacao);
    }
    return $this->sessao;
  }

  /**
     * Funcao para por o id na sessao
     * @return Json
     */
  public function kleoAction() {
    $sessao = self::getSessao();
    $request = $this->getRequest();
    $response = $this->getResponse();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $action = $post_data[self::stringAction];
        $id = $post_data[self::stringId];
        $sessao->idSessao = $id;
        $response->setContent(Json::encode(
          array(
            'response' => 'true',
            'url' => '/' . $action,
          )));
      } catch (Exception $exc) {
        echo $exc->getMessage();
      }
    }
    return $response;
  }

  public function mostrarMensagensDeErroFormulario($messages) {
    foreach ($messages as $campo => $message) {
      echo "<br />$campo:";
      foreach ($message as $key => $value) {
        echo "<br />$key => $value";
      }
    }
  }

  /**
     * Recupera ORM
     * @return EntityManager
     */
  public function getDoctrineORMEntityManager() {
    return $this->_doctrineORMEntityManager;
  }

  public function getRepositorio() {
    if (empty($this->repositorio)) {
      $this->repositorio = new RepositorioORM($this->getDoctrineORMEntityManager());
    }
    return $this->repositorio;
  }

  /**
     * Seta o layout da administracao
     */
  public function setLayoutSite() {
    $this->layout('layout/site');
  }

}
