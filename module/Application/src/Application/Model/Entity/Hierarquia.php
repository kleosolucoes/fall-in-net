<?php

namespace Application\Model\Entity;

/**
 * Nome: Hierarquia.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela hierarquia 
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="hierarquia")
 */
class Hierarquia extends KleoEntity {

    const ATIVO_SEM_REUNIAO = 1;
    const ATIVO_COM_REUNIAO = 2;
  
    /**
     * @ORM\OneToMany(targetEntity="PessoaHierarquia", mappedBy="hierarquia") 
     */
    protected $pessoaHierarquia;

    public function __construct() {
        $this->pessoaHierarquia = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getPessoaHierarquia() {
        return $this->pessoaHierarquia;
    }

    function setPessoaHierarquia($pessoaHierarquia) {
        $this->pessoaHierarquia = $pessoaHierarquia;
    }

}
