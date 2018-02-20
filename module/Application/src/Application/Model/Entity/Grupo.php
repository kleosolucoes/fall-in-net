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

  function getGrupoPaiFilhoFilhosAtivos() {
    $grupoPaiFilhoFilhosAtivos = null;
    $grupoPaiFilhoFilhos = $this->getGrupoPaiFilhoFilhos();
    if ($grupoPaiFilhoFilhos) {
      foreach ($grupoPaiFilhoFilhos as $gpf) {
        if ($gpf->verificarSeEstaAtivo()) {
          $grupoPaiFilhoFilhosAtivos[] = $gpf;
        }
      }
    }
    return $grupoPaiFilhoFilhosAtivos;
  }

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
  function getGrupoPessoaAtivasNoPeriodo($inicioDoCiclo, $fimDoCiclo) {
    $pessoas = null;
    $inicioDoCiclo--;
    if (!empty($this->getGrupoPessoa())) {
      foreach ($this->getGrupoPessoa() as $grupoPessoa) {
        $verificacaoData = false;
        $dataCriacao = $grupoPessoa->getData_criacaoFormatoBandoDeDados();

        for($indiceDias = $inicioDoCiclo;$indiceDias <= $fimDoCiclo;$indiceDias++){
          $diaParaComparar = date('Y-m-d', strtotime('now +'.$indiceDias.' days'));
          if($dataCriacao == $diaParaComparar){
            $verificacaoData = true;
          }
        }

        if ($grupoPessoa->verificarSeEstaAtivo() && $verificacaoData) {
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
