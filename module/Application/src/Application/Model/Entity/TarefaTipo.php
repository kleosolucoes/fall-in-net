<?php

namespace Application\Model\Entity;

/**
 * Nome: TarefaTipo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela tarefa_tipo 
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="tarefa_tipo")
 */
class TarefaTipo extends KleoEntity {


    /**
     * @ORM\OneToMany(targetEntity="Tarefa", mappedBy="tarefaTipo") 
     */
    protected $tarefa;

    public function __construct() {
        $this->tarefa = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getTarefa() {
        return $this->tarefa;
    }

    function setTarefa($tarefa) {
        $this->tarefa = $tarefa;
    }

}
