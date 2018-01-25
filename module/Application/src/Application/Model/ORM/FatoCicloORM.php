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

  public function encontrarPorNumeroIdentificadorEDataCriacao($numeroIdentificador, $dia, RepositorioORM $repositorioORM) {
    try {
      $resposta = $this->getEntityManager()
        ->getRepository($this->getEntity())
        ->findOneBy(
        array(
          'numero_identificador' => $numeroIdentificador,
          'data_criacao' => $dia,
        ));
      if (empty($resposta)) {
        $resposta = $this->criarFatoCiclo($numeroIdentificador, $dia, $repositorioORM);
      }
      return $resposta;
    } catch (Exception $exc) {
      echo $exc->getTraceAsString();
    }
  }

  /**
     * Localizar fato_ciclo por numeroIdentificador
     * @param string $numeroIdentificador
     * @param int $periodo
     * @param int $tipoComparacao
     * @return array
     */
  public function montarRelatorioPorNumeroIdentificador($numeroIdentificador, $periodoInicial, $tipoComparacao, $periodoFinal = null) {
    $dimensaoTipoCelula = 1;
    $dimensaoTipoDomingo = 4;
    $dqlBase = "SELECT "
      . "SUM(d.lider) lideres, "
      . "SUM(d.visitante) visitantes, "
      . "SUM(d.consolidacao) consolidacoes, "
      . "SUM(d.membro) membros "
      . "FROM  " . Constantes::$ENTITY_FATO_CICLO . " fc "
        . "JOIN fc.dimensao d "
        . "WHERE "
        . "d.dimensaoTipo = #dimensaoTipo "
        . "AND fc.numero_identificador #tipoComparacao ?1 "
        . "AND fc.data_inativacao is null "
        . "#data";
    try {

      if ($tipoComparacao == 1) {
        $dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', '=', $dqlBase);
      }
      if ($tipoComparacao == 2) {
        $dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', 'LIKE', $dqlBase);
        $numeroIdentificador .= '%';
      }

      $resultadoPeriodo = Funcoes::montaPeriodo($periodoInicial);
      $dataDoPeriodo = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
      $dataDoPeriodoFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodo);

      if ($periodoFinal === null) {
        $dqlAjustadaTipoComparacao = str_replace('#data', 'AND fc.data_criacao = ?2 ', $dqlAjustadaTipoComparacao);
      } else {
        $resultadoPeriodoFinal = Funcoes::montaPeriodo($periodoFinal);
        $dataDoPeriodoFinal = $resultadoPeriodoFinal[6] . '-' . $resultadoPeriodoFinal[5] . '-' . $resultadoPeriodoFinal[4];
        $stringDatas = "AND fc.data_criacao >= ?2 AND fc.data_criacao <= '$dataDoPeriodoFinal' ";
        $dqlAjustadaTipoComparacao = str_replace('#data', $stringDatas, $dqlAjustadaTipoComparacao);
        $dataDoPeriodoFormatada = $dataDoPeriodo;
      }
      for ($indice = $dimensaoTipoCelula; $indice <= $dimensaoTipoDomingo; $indice++) {
        $dqlAjustada = str_replace('#dimensaoTipo', $indice, $dqlAjustadaTipoComparacao);
        $result[$indice] = $this->getEntityManager()->createQuery($dqlAjustada)
          ->setParameter(1, $numeroIdentificador)
          ->setParameter(2, $dataDoPeriodoFormatada)
          ->getResult();
      }
      return $result;
    } catch (Exception $exc) {
      echo $exc->getTraceAsString();
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

  public function verificaFrequenciasPorCelulaEPeriodo($periodoInicial, $eventoId, $periodoFinal = 0) {
    $resultadoPeriodoInicial = Funcoes::montaPeriodo($periodoInicial);
    $dataDoPeriodoInicial = $resultadoPeriodoInicial[3] . '-' . $resultadoPeriodoInicial[2] . '-' . $resultadoPeriodoInicial[1];

    $resultadoPeriodoFinal = Funcoes::montaPeriodo($periodoFinal);
    $dataDoPeriodoFinal = $resultadoPeriodoFinal[6] . '-' . $resultadoPeriodoFinal[5] . '-' . $resultadoPeriodoFinal[4];

    $dataDoInicioFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodoInicial);
    $dataDoFimFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodoFinal);

    $dqlBase = "SELECT "
      . "ef.frequencia "
      . "FROM  " . Constantes::$ENTITY_EVENTO_FREQUENCIA . " ef "
        . "WHERE "
        . "ef.evento_id = ?1 AND "
        . "ef.dia >= ?2 AND ef.dia <= ?3 ";

    $resultados = $this->getEntityManager()->createQuery($dqlBase)
      ->setParameter(1, (int) $eventoId)
      ->setParameter(2, $dataDoInicioFormatada)
      ->setParameter(3, $dataDoFimFormatada)
      ->getResult();

    $somaResultado = 0;
    foreach ($resultados as $resultado) {
      if ($resultado['frequencia'] == 'S') {
        $somaResultado++;
      }
    }
    return $somaResultado;
  }

}