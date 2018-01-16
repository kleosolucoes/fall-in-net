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

    const BISPO = 1;
    const PASTOR = 2;
    const MISSIONARIO = 3;
    const DIACONO = 4;
    const OBREIRO = 5;
    const LIDER_DE_CELULA = 6;
    const LIDER_EM_TREINAMENTO = 7;

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
