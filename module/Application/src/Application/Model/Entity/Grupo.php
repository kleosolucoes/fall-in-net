<?php

namespace Application\Model\Entity;

/**
 * Nome: Grupo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela grupo
 */
use Application\Controller\Helper\Funcoes;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="grupo")
 */
class Grupo extends KleoEntity {

  /**
     * @ORM\OneToMany(targetEntity="GrupoResponsavel", mappedBy="grupo")
     */
  protected $grupoResponsavel;

  /**
     * @ORM\OneToMany(targetEntity="GrupoEvento", mappedBy="grupo")
     */
  protected $grupoEvento;

  /**
     * @ORM\OneToMany(targetEntity="GrupoPessoa", mappedBy="grupo")
     */
  protected $grupoPessoa;

  /**
     * @ORM\OneToMany(targetEntity="GrupoPaiFilho", mappedBy="grupoPaiFilhoPai")
     */
  protected $grupoPaiFilhoFilhos;

  /**
     * @ORM\OneToMany(targetEntity="GrupoPaiFilho", mappedBy="grupoPaiFilhoFilho")
     */
  protected $grupoPaiFilhoPai;

  public function __construct() {
    $this->grupoResponsavel = new ArrayCollection();
    $this->grupoEvento = new ArrayCollection();
    $this->grupoPessoa = new ArrayCollection();
    $this->grupoPaiFilhoFilhos = new ArrayCollection();
    $this->grupoPaiFilhoPai = new ArrayCollection();
  }

  /**
     * Retorna o grupo responsavel do grupo
     * @return GrupoResponsavel
     */
  function getGrupoResponsavel() {
    return $this->grupoResponsavel;
  }

  /**
     * Retorna o grupo responsavel ativo
     * @return GrupoResponsavel
     */
  function getGrupoResponsavelAtivo() {
    $grupoResponsavel = null;
    foreach ($this->getGrupoResponsavel() as $gr) {
      if ($gr->verificarSeEstaAtivo()) {
        $grupoResponsavel = $gr;
        break;
      }
    }
    return $grupoResponsavel;
  }

  /**
     * Recupera os filhos ativos por periodo
     * @return Pessoa[]
     */
  function getGrupoPaiFilhoFilhosAtivos($periodo = -1) {
    $grupoPaiFilhoFilhosAtivos = null;
    /* Responsabilidades */
    $grupoPaiFilhoFilhos = $this->getGrupoPaiFilhoFilhos();
    if ($grupoPaiFilhoFilhos) {
      /* Verificar responsabilidades ativas */
      foreach ($grupoPaiFilhoFilhos as $gpf) {
        $arrayPeriodo = Funcoes::montaPeriodo($periodo);
        if ($gpf->verificarSeEstaAtivo()) {
          $stringFimDoPeriodo = $arrayPeriodo[6] . '-' . $arrayPeriodo[5] . '-' . $arrayPeriodo[4];
          $dataDoInicioDoPeriodoParaComparar = strtotime($stringFimDoPeriodo);
          $dataDoGrupoPaiFilhoCriacaoParaComparar = strtotime($gpf->getData_criacaoStringPadraoBanco());
          if ($dataDoGrupoPaiFilhoCriacaoParaComparar <= $dataDoInicioDoPeriodoParaComparar) {
            $grupoPaiFilhoFilhosAtivos[] = $gpf;
          }
        } else {
          /* Inativo */
          $stringComecoDoPeriodo = $arrayPeriodo[3] . '-' . $arrayPeriodo[2] . '-' . $arrayPeriodo[1];
          $dataDoInicioDoPeriodoParaComparar = strtotime($stringComecoDoPeriodo);
          $dataDoGrupoGrupoPaiFilhoInativadoParaComparar = strtotime($gpf->getData_inativacaoStringPadraoBanco());
          if ($dataDoGrupoGrupoPaiFilhoInativadoParaComparar >= $dataDoInicioDoPeriodoParaComparar) {
            $grupoPaiFilhoFilhosAtivos[] = $gpf;
          }
        }
      }
    }
    return $grupoPaiFilhoFilhosAtivos;
  }

  /**
     * Metódo que retorna os filhos no periodo do mês.
     * @method getGrupoPaiFilhoFilhosPorMes
     * @param  int $mes
     * @return Grupo grupos no período do mês inputado.
     */
  function getGrupoPaiFilhoFilhosPorMes($mes) {
    $grupoPaiFilhoFilhosAtivos = null;
    /* Responsabilidades */
    $grupoPaiFilhoFilhos = $this->getGrupoPaiFilhoFilhos();
    if ($grupoPaiFilhoFilhos) {
      /* Verificar responsabilidades ativas */
      foreach ($grupoPaiFilhoFilhos as $gpf) {
        $arrayPeriodo = Funcoes::montaPeriodo($periodo);
        if ($gpf->verificarSeEstaAtivo()) {
          $stringFimDoPeriodo = date('Y') . '-' . $mes . '-' . cal_days_in_month(CAL_GREGORIAN, $mes, date('Y'));
          $dataDoFimDoPeriodoParaComparar = strtotime($stringFimDoPeriodo);
          $dataDoGrupoPaiFilhoCriacaoParaComparar = strtotime($gpf->getData_criacaoStringPadraoBanco());
          if ($dataDoGrupoPaiFilhoCriacaoParaComparar <= $dataDoFimDoPeriodoParaComparar) {
            $grupoPaiFilhoFilhosAtivos[] = $gpf;
          }
        } else {
          /* Inativo */
          $stringFimDoPeriodo = date('Y') . '-' . $mes . '-' . cal_days_in_month(CAL_GREGORIAN, $mes, date('Y'));
          $dataDoFimDoPeriodoParaComparar = strtotime($stringFimDoPeriodo);
          $dataDoGrupoGrupoPaiFilhoInativadoParaComparar = strtotime($gpf->getData_inativacaoStringPadraoBanco());
          if ($dataDoGrupoGrupoPaiFilhoInativadoParaComparar <= $dataDoFimDoPeriodoParaComparar) {
            $grupoPaiFilhoFilhosAtivos[] = $gpf;
          }
        }
      }
    }
    return $grupoPaiFilhoFilhosAtivos;
  }

  function getGrupoPaiFilhoFilhosAtivosReal() {
    $grupoPaiFilhoFilhosAtivos = null;
    /* Responsabilidades */
    $grupoPaiFilhoFilhos = $this->getGrupoPaiFilhoFilhos();
    if ($grupoPaiFilhoFilhos) {
      /* Verificar responsabilidades ativas */
      foreach ($grupoPaiFilhoFilhos as $gpf) {
        if ($gpf->verificarSeEstaAtivo()) {
          $grupoPaiFilhoFilhosAtivos[] = $gpf;
        }
      }
    }
    return $grupoPaiFilhoFilhosAtivos;
  }

  /**
     * Recupera os filhos ativos
     * @return Pessoa[]
     */
  function getGrupoPaiFilhoPaiAtivo() {
    $grupoPaiFilhoPaiAtivo = null;
    /* Responsabilidades */
    $grupoPaiFilhoPais = $this->getGrupoPaiFilhoPai();
    if (count($grupoPaiFilhoPais) > 0) {
      /* Verificar responsabilidades ativas */
      foreach ($grupoPaiFilhoPais as $gpp) {
        if ($gpp->verificarSeEstaAtivo()) {
          $grupoPaiFilhoPaiAtivo = $gpp;
          break;
        }
      }
    }
    //        if (!$grupoPaiFilhoPaiAtivo) {
    //            foreach ($grupoPaiFilhoPais as $gpp) {
    //                if (!$gpp->verificarSeEstaAtivo()) {
    //                    $grupoPaiFilhoPaiAtivo = $gpp;
    //                    break;
    //                }
    //            }
    //        }
    return $grupoPaiFilhoPaiAtivo;
  }

  function getNomeLideresAtivos() {
    $pessoas = $this->getPessoasAtivas();
    $nomes = '';
    $contador = 1;
    $inativa = false;

    if (!$pessoas) {
      $inativa = true;
      $pessoas = $this->getPessoasInativas();
      $dataInativacao = $pessoas[0]->getGrupoResponsavel()[0]->getData_inativacaoStringPadraoBrasil();
    }
    foreach ($pessoas as $pessoa) {
      if ($contador === 2) {
        $nomes .= ' e ';
      }
      if (count($pessoas) == 2) {
        $nomes .= $pessoa->getNomePrimeiro();
      } else {
        $nomes .= $pessoa->getNomePrimeiroUltimo();
      }
      $contador++;
    }
    if ($inativa) {
      $nomes = $nomes . ' (INATIVO - ' . $dataInativacao . ')';
    }
    return $nomes;
  }

  function setGrupoResponsavel($grupoResponsavel) {
    $this->grupoResponsavel = $grupoResponsavel;
  }

  /**
     * Retorna o grupo evento
     * @return GrupoEvento
     */
  function getGrupoEvento() {
    return $this->grupoEvento;
  }

  /**
     * Retorna o grupo evento ordenados por dia da semana
     * @return GrupoEvento
     */
  function getGrupoEventoAcima() {
    $grupoSelecionado = $this;

    if ($grupoSelecionado->getGrupoResponsavelAtivo()->getPessoa()->getPessoaHierarquiaAtivo()->getHierarquia()->getId() === HIERARQUIA::ATIVO_SEM_REUNIAO) {
      while ($grupoSelecionado->getGrupoResponsavelAtivo()->getPessoa()->getPessoaHierarquiaAtivo()->getHierarquia()->getId() === HIERARQUIA::ATIVO_SEM_REUNIAO) {
        $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
        if ($grupoSelecionado->getGrupoResponsavelAtivo()->getPessoa()->getPessoaHierarquiaAtivo()->getHierarquia()->getId() === HIERARQUIA::ATIVO_COM_REUNIAO) {
          break;
        }
      }
    }

    return $grupoSelecionado->getGrupoEventoAtivos();
  }

  /**
     * Retorna o grupo evento
     * @return GrupoEvento
     */
  function getGrupoEventoAtivos() {
    $grupoEventos = null;
    foreach ($this->getGrupoEvento() as $grupoEvento) {
      if ($grupoEvento->verificarSeEstaAtivo()) {
        $grupoEventos[] = $grupoEvento;
      }
    }
    return $grupoEventos;
  }

  /**
     * Verifica se o grupo participa do evento informado
     * @param int $idEvento
     * @return boolean
     */
  function verificaSeParticipaDoEvento($idEvento) {
    $resposta = false;
    $id = (int) $idEvento;

    if ($this->getGrupoEventoAtivos()) {
      foreach ($this->getGrupoEventoAtivos() as $ge) {
        if ($ge->getEvento_id() == $id) {
          $resposta = true;
        }
      }
    }
    return $resposta;
  }



  function getGrupoPessoasNoPeriodo($periodo) { 

    $grupoPessoasNoPeriodo = array();

    $grupoPessoas = $this->getGrupoPessoa();
    if (!empty($grupoPessoas)) {
      foreach ($grupoPessoas as $grupoPessoa) {
        $dataDoGrupoPessoaParaComparar = strtotime($grupoPessoa->getData_criacaoStringPadraoBanco());
        $arrayPeriodo = Funcoes::montaPeriodo($periodo);
        $stringPeriodo = $arrayPeriodo[3] . '-' . $arrayPeriodo[2] . '-' . $arrayPeriodo[1];
        $dataDoInicioDoPeriodoParaComparar = strtotime($stringPeriodo);

        /* Criando antes do começo do periodo */
        $validacaoDataCriacao = false;
        if ($dataDoGrupoPessoaParaComparar <= $dataDoInicioDoPeriodoParaComparar) {
          $validacaoDataCriacao = true;
        }

        /* Criando no periodo */
        $stringFimPeriodo = $arrayPeriodo[6] . '-' . $arrayPeriodo[5] . '-' . $arrayPeriodo[4];
        $dataDoFimParaComparar = strtotime($stringFimPeriodo);
        if ($dataDoGrupoPessoaParaComparar > $dataDoInicioDoPeriodoParaComparar && $dataDoGrupoPessoaParaComparar <= $dataDoFimParaComparar) {
          $validacaoDataCriacao = true;
        }

        /* Se esta inativo */
        $validacaoAtivoEDataInativacao = true;
        if (!$grupoPessoa->verificarSeEstaAtivo()) {
          /* Inativado no periodo */
          $dataDoGrupoPessoaInativacaoParaComparar = strtotime($grupoPessoa->getData_inativacaoStringPadraoBanco());
          if ($dataDoGrupoPessoaInativacaoParaComparar < $dataDoInicioDoPeriodoParaComparar) {
            $validacaoAtivoEDataInativacao = false;
          }

          /* Revisao de vidas */
          $semOutraPessoa = false;
          foreach ($grupoPessoas as $grupoPessoaParaVerificar) {
            if ($grupoPessoaParaVerificar->getPessoa()->getId() === $grupoPessoa->getPessoa()->getId()) {
              $semOutraPessoa = true;
            }
          }

          if ($semOutraPessoa) {
            $validacaoAtivoEDataInativacao = false;
          }
        }

        /* Periodo a frente */
        if (!$validacaoDataCriacao) {
          $arrayPeriodoAFrente = Funcoes::montaPeriodo($periodo + 1);
          $stringPeriodoAFrente = $arrayPeriodoAFrente[3] . '-' . $arrayPeriodoAFrente[2] . '-' . $arrayPeriodoAFrente[1];
          $dataDoInicioDoPeriodoAFrenteParaComparar = strtotime($stringPeriodoAFrente);
          if ($dataDoGrupoPessoaParaComparar >= $dataDoInicioDoPeriodoAFrenteParaComparar) {
            $validacaoDataCriacao = true;
          }
        }

        if ($validacaoDataCriacao && $validacaoAtivoEDataInativacao) {
          $grupoPessoasNoPeriodo[] = $grupoPessoa;
        }
      }
    }
    return $grupoPessoasNoPeriodo;
  }


  function setGrupoEvento($grupoEvento) {
    $this->grupoEvento = $grupoEvento;
  }

  /**
     * Retorna o grupo pessoa
     * @return GrupoPessoa
     */
  function getGrupoPessoa() {
    return $this->grupoPessoa;
  }

  /**
     * Retorna o grupo pessoa ativas no mes infomado
     * @return GrupoPessoa
     */
  function getGrupoPessoaAtivasNoPeriodoDe1Semanas() {
    $pessoas = null;
    $dataDePeriodoValido = strtotime('now -7 days'); 
    if (!empty($this->getGrupoPessoa())) {
      foreach ($this->getGrupoPessoa() as $grupoPessoa) {
        /* Condição para data de cadastro */
        $verificacaoData = false;
        $dataCriacao = strtotime($grupoPessoa->getData_criacaoFormatoBandoDeDados());
        if($dataCriacao >= $dataDePeriodoValido){
          $verificacaoData = true;
        }

        $condicao[1] = ($grupoPessoa->verificarSeEstaAtivo() && $verificacaoData);
        if ($condicao[1]) {
          $pessoas[] = $grupoPessoa;
        }
      }
    }
    $this->setGrupoPessoa($pessoas);
    return $this->getGrupoPessoa();
  }

  function setGrupoPessoa($grupoPessoa) {
    $this->grupoPessoa = $grupoPessoa;
  }

  function getEventos() {
    return $this->eventos;
  }

  function setEventos($eventos) {
    $this->eventos = $eventos;
  }

  /**
     * Pega os grupos filhos
     * @return GrupoPaiFilho
     */
  function getGrupoPaiFilhoFilhos() {
    return $this->grupoPaiFilhoFilhos;
  }

  function setGrupoPaiFilhoFilhos($grupoPaiFilhoFilhos) {
    $this->grupoPaiFilhoFilhos = $grupoPaiFilhoFilhos;
  }

  /**
     * Pega o grupo Pai
     * @return GrupoPaiFilho
     */
  function getGrupoPaiFilhoPai() {
    return $this->grupoPaiFilhoPai;
  }

  function setGrupoPaiFilhoPai($grupoPaiFilhoPai) {
    $this->grupoPaiFilhoPai = $grupoPaiFilhoPai;
  }
}
