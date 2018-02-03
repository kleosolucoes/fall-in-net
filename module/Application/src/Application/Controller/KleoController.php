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
use Exception;

/**
 * Nome: KleoController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle com propriedade do ORM
 */
class KleoController extends AbstractActionController {

  private $_doctrineORMEntityManager;
  private $sessao;
  private $repositorio;


  const nomeAFabrica = 'AFábrica';
  const nomeAplicacao = 'URSA';
  const nomeAplicacaoFormatado = 'U.R.S.A.';
  const nomeAplicacaoDescricao = 'Unidade de Relacionamento e Supervisão Avançada';
  const stringFormulario = 'formulario';
  const stringAction = 'action';
  const stringId = 'id';
  const stringToken = 'token';
  const stringLogin = 'login';
  const stringIndex = 'index';
  const stringAgenda = 'agenda';
  const stringPontes = 'pontes';
  const stringPontesParaCadastro = 'pontesParaCadastro';
  const stringGrupoPessoas = 'grupoPessoas';
  const stringInicioDoCiclo = 'inicioDoCiclo';
  const stringFimDoCiclo = 'fimDoCiclo';
  const stringRelatorio = 'relatorio';
  const stringMensagem = 'mensagem';
  const controllerPub = 'Application\Controller\Pub';
  const controllerAdm = 'Application\Controller\Adm';
  const rotaPub = 'pub';
  const rotaAdm = 'adm';
  const metaPonte = 1;
  const metaProspecto = 4;
  const relatorioPonte = 'ponte';
  const relatorioPontePerformance = 'pontePerformance';
  const relatorioProspecto = 'prospecto';
  const relatorioProspectoPerformance = 'prospectoPerformance';
  const relatorioLigacao = 'ligacao';
  const relatorioLigacaoPerformance = 'ligacaoPerformance';
  const relatorioMensagem = 'mensagem';
  const relatorioMensagemPerformance = 'mensagemPerformance';
  const relatorioFrequencia = 'frequencia';
  const relatorioCliqueLigacao = 'cliqueLigacao';
  const relatorioCliqueMensagem = 'cliqueMensagem';

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

  public static function diaDaSemanaPorDia($dia, $tipo = 0) {
    $resposta = '';
    switch ($dia) {
      case 1:$resposta = 'SEG';
        if ($tipo == 1) {
          $resposta = 'SEGUNDA-FEIRA';
        }
        break;
      case 2:$resposta = 'TER';
        if ($tipo == 1) {
          $resposta = 'TERÇA-FEIRA';
        }
        break;
      case 3:$resposta = 'QUA';
        if ($tipo == 1) {
          $resposta = 'QUARTA-FEIRA';
        }
        break;
      case 4:$resposta = 'QUI';
        if ($tipo == 1) {
          $resposta = 'QUINTA-FEIRA';
        }
        break;
      case 5:$resposta = 'SEX';
        if ($tipo == 1) {
          $resposta = 'SEXTA-FEIRA';
        }
        break;
      case 6:$resposta = 'SÁB';
        if ($tipo == 1) {
          $resposta = 'SÁBADO';
        }
        break;
      case 7:$resposta = 'DOM';
        if ($tipo == 1) {
          $resposta = 'DOMINGO';
        }
        break;
    }
    return $resposta;
  }

  public static function mesPorExtenso($mes, $tipo = 0) {
    $resposta = '';
    switch ($mes) {
      case 1:$resposta = 'JAN';
        if ($tipo == 1) {
          $resposta = 'JANEIRO';
        }
        break;
      case 2:$resposta = 'FEV';
        if ($tipo == 1) {
          $resposta = 'FEVEREIRO';
        }
        break;
      case 3:$resposta = 'MAR';
        if ($tipo == 1) {
          $resposta = 'MARÇO';
        }
        break;
      case 4:$resposta = 'ABR';
        if ($tipo == 1) {
          $resposta = 'ABRIL';
        }
        break;
      case 5:$resposta = 'MAI';
        if ($tipo == 1) {
          $resposta = 'MAIO';
        }
        break;
      case 6:$resposta = 'JUN';
        if ($tipo == 1) {
          $resposta = 'JUNHO';
        }
        break;
      case 7:$resposta = 'JUL';
        if ($tipo == 1) {
          $resposta = 'JULHO';
        }
        break;
      case 8:$resposta = 'AGO';
        if ($tipo == 1) {
          $resposta = 'AGOSTO';
        }
        break;
      case 9:$resposta = 'SET';
        if ($tipo == 1) {
          $resposta = 'SETEMBRO';
        }
        break;
      case 10:$resposta = 'OUT';
        if ($tipo == 1) {
          $resposta = 'OUTUBRO';
        }
        break;
      case 11:$resposta = 'NOV';
        if ($tipo == 1) {
          $resposta = 'NOVEMBRO';
        }
        break;
      case 12:$resposta = 'DEZ';
        if ($tipo == 1) {
          $resposta = 'DEZEMBRO';
        }
        break;
    }
    return $resposta;
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
