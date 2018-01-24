<?php

namespace Application\Model\Entity;

/**
 * Nome: Tarefa.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela tarefa
 */

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="tarefa")
 */
class Tarefa extends KleoEntity {

  const LIGAR = 1;
  const MENSAGEM = 2;
  public function __construct() {
    $this->setRealizada('N');
  }
  /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="tarefa")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
  private $pessoa;

  /**
     * @ORM\ManyToOne(targetEntity="TarefaTipo", inversedBy="tarefa")
     * @ORM\JoinColumn(name="tarefa_tipo_id", referencedColumnName="id")
     */
  private $tarefaTipo;

  /** @ORM\Column(type="integer") */
  protected $pessoa_id;

  /** @ORM\Column(type="integer") */
  protected $tarefa_tipo_id;

  /** @ORM\Column(type="string") */
  protected $realizada;

  /** @ORM\Column(type="datetime", name="data_alteracao") */
  protected $data_alteracao;
  /** @ORM\Column(type="string") */
  protected $hora_alteracao;
  /**
     * Seta data e hora de criação
     */
  function setDataEHoraDeAlteracao() {
    $timeNow = new DateTime();
    $this->setData_alteracao($timeNow);
    $this->setHora_alteracao($timeNow->format('H:s:i'));
  }

  function setData_alteracao($data_alteracao) {
    $this->data_alteracao = $data_alteracao;
  }
  
  function setHora_alteracao($hora_alteracao) {
    $this->hora_alteracao = $hora_alteracao;
  }

  function getData_alteracao() {
    $this->data_alteracao;
  }
  
  function getHora_alteracao() {
    $this->hora_alteracao;
  }


  function getPessoa() {
    return $this->pessoa;
  }

  function getTarefaTipo() {
    return $this->tarefaTipo;
  }

  function getPessoa_id() {
    return $this->pessoa_id;
  }

  function getTarefa_tipo_id() {
    return $this->tarefa_tipo_id;
  }

  function getRealizada() {
    return $this->realizada;
  }

  function setPessoa($pessoa) {
    $this->pessoa = $pessoa;
  }

  function setTarefaTipo($tarefaTipo) {
    $this->tarefaTipo = $tarefaTipo;
  }

  function setPessoa_id($pessoa_id) {
    $this->pessoa_id = $pessoa_id;
  }

  function setTarefa_tipo_id($tarefa_tipo_id) {
    $this->tarefa_tipo_id = $tarefa_tipo_id;
  }

  function setRealizada($realizada) {
    $this->realizada = $realizada;
  }

}
