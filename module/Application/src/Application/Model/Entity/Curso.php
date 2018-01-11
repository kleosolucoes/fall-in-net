<?php

namespace Application\Model\Entity;

/**
 * Nome: Curso.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Entidade anotada da tabela curso
 */

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="curso")
 */
class Curso extends CircuitoEntity { 
    
    /** @ORM\Column(type="string") */
    protected $nome;
    
    /** @ORM\Column(type="integer") */
    protected $pessoa_id;
    
    /**
     * @ORM\OneToMany(targetEntity="Disciplina", mappedBy="curso")  
     */
    protected $disciplina;
    
    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="curso")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;
    
    public function __construct() {
        $this->disciplina = new ArrayCollection();
    }
    
    function getPessoa_id() {
        return $this->pessoa_id;
    }

    function getPessoa() {
        return $this->pessoa;
    }

    function setPessoa_id($pessoa_id) {
        $this->pessoa_id = $pessoa_id;
    }

    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }
    
    function getDisciplina() {
        return $this->disciplina;
    }

    function setDisciplina($disciplina) {
        $this->disciplina = $disciplina;
    }

}
