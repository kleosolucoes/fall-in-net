<?php

namespace Application\Model\ORM;

use Application\Model\Entity\FatoCiclo;
use Application\Controller\KleoController;
use DateTime;
use Exception;
use Zend\Session\Container;

/**
 * Nome: FatoCicloORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity fato_ciclo
 */
class FatoCicloORM extends KleoORM {

  /**
     * Localizar fato_ciclo por numeroIdentificador
     * @return array
     */
  public function montarRelatorioPorNumeroIdentificador($numeroIdentificador, $dataIncial, $dataFinal, $tipoComparacao) {
    $dqlBase = "SELECT "
      . "SUM(fc.ponte) ponte, "
      . "SUM(fc.prospecto) prospecto, "
      . "SUM(fc.ligacao) ligacao, "
      . "SUM(fc.mensagem) mensagem, "
      . "SUM(fc.frequencia) frequencia, "
      . "SUM(fc.clique_ligacao) clique_ligacao, "
      . "SUM(fc.clique_mensagem) clique_mensagem "
      . "FROM Application\Model\Entity\FatoCiclo fc "
      . "WHERE "
      . " fc.numero_identificador #tipoComparacao ?1 "
      . " AND fc.data_inativacao is null "
      . " AND fc.data_criacao >= ?2 AND fc.data_criacao <= ?3 ";
    try {

      if ($tipoComparacao == 1) {
        $dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', '=', $dqlBase);
      }
      if ($tipoComparacao == 2) {
        $dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', 'LIKE', $dqlBase);
        $numeroIdentificador .= '%';
      }

      $dataInicialFormatada = DateTime::createFromFormat('Y-m-d', $dataIncial);
      $dataFinalFormatada = DateTime::createFromFormat('Y-m-d', $dataFinal);
      $result = $this->getEntityManager()->createQuery($dqlAjustadaTipoComparacao)
        ->setParameter(1, $numeroIdentificador)
        ->setParameter(2, $dataInicialFormatada)
        ->setParameter(3, $dataFinalFormatada)
        ->getResult();
      return $result;
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

  /**
     * Montar numeroIdentificador
     */
  public function montarNumeroIdentificador(RepositorioORM $repositorioORM, $grupo = null) {
    $numeroIdentificador = '';
    $tamanho = 8;
    $grupoSelecionado = null;
    if ($grupo === null) {
      $sessao = new Container(KleoController::nomeAplicacao);
      $idPessoa = $sessao->idPessoa;
      $pessoaLogada = $repositorioORM->getPessoaORM()->encontrarPorId($idPessoa);
      $grupoSelecionado = $pessoaLogada->getResponsabilidadesAtivas()[0]->getGrupo();
    } else {
      $grupoSelecionado = $grupo;
    }
    try {
      $numeroIdentificador = str_pad($grupoSelecionado->getId(), $tamanho, 0, STR_PAD_LEFT);
      while ($grupoSelecionado->getGrupoPaiFilhoPaiAtivo()) {
        $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
        $numeroIdentificador = str_pad($grupoSelecionado->getId(), $tamanho, 0, STR_PAD_LEFT).$numeroIdentificador;
      }
      return $numeroIdentificador;
    } catch (Exception $exc) {
      echo $exc->getTraceAsString();
    }
  }

  /**
     * Criar fato ciclo
     */
  public function criarFatoCiclo($numeroIdentificador, $campo, $valor) {
    try {
      $fatoCiclo = new FatoCiclo();
      $fatoCiclo->setNumero_identificador($numeroIdentificador);
      switch($campo){
        case FatoCiclo::LIGACAO:
          $fatoCiclo->setLigacao($valor);
          break;
        case FatoCiclo::MENSAGEM:
          $fatoCiclo->setMensagem($valor);
          break;
        case FatoCiclo::PONTE:
          $fatoCiclo->setPonte($valor);
          break;
        case FatoCiclo::PROSPECTO:
          $fatoCiclo->setProspecto($valor);
          break;
        case FatoCiclo::FREQUENCIA:
          $fatoCiclo->setFrequencia($valor);
          break;
        case FatoCiclo::CLIQUE_LIGACAO:
          $fatoCiclo->setClique_ligacao($valor);
          break;
        case FatoCiclo::CLIQUE_MENSAGEM:
          $fatoCiclo->setClique_mensagem($valor);
          break;
      }
      $this->persistir($fatoCiclo);
      return $fatoCiclo;
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }
}