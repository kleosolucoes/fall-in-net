<?php

namespace Application\Model\Entity;

/**
 * Nome: FatoCiclo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela fato_ciclo 
 */

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_ciclo")
 */
class FatoCiclo extends CircuitoEntity {

  /** @ORM\Column(type="string") */
  protected $numero_identificador;

  /** @ORM\Column(type="integer") */
  protected $ligacao;

  /** @ORM\Column(type="integer") */
  protected $mensagem;

  /** @ORM\Column(type="integer") */
  protected $ponte;

  /** @ORM\Column(type="integer") */
  protected $prospecto;
  
  /** @ORM\Column(type="integer") */
  protected $frequencia;

  function getNumero_identificador() {
    return $this->numero_identificador;
  }

  function getLigacao() {
    return $this->ligacao;
  }

  function getMensagem() {
    return $this->mensagem;
  }

  function getPonte() {
    return $this->ponte;
  }

  function getProspecto() {
    return $this->prospecto;
  }
  
  function getFrequencia() {
    return $this->frequencia;
  }

  function setNumero_identificador($numero_identificador) {
    $this->numero_identificador = $numero_identificador;
  }

  function setLigacao($ligacao) {
    $this->ligacao = $ligacao;
  }

  function setMensagem($mensagem) {
    $this->mensagem = $mensagem;
  }

  function setPonte($ponte) {
    $this->ponte = $ponte;
  }

  function setProspecto($prospecto) {
    $this->prospecto = $prospecto;
  }

  function setFrequencia($frequencia) {
    $this->frequencia = $frequencia;
  }

}