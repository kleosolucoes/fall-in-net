<?php

namespace Application\Model\Entity;

/**
 * Nome: GrupoPessoaTipo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela grupo_pessoa_tipo
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="grupo_pessoa_tipo")
 */
class GrupoPessoaTipo extends KleoEntity {

    const PONTE = 1;
    const PROSPECTO = 2;

    /**
     * @ORM\OneToMany(targetEntity="GrupoPessoa", mappedBy="grupoPessoaTipo") 
     */
    protected $grupoPessoa;

    public function __construct() {
        $this->grupoPessoa = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    function getGrupoPessoa() {
        return $this->grupoPessoa;
    }

    function getNome() {
        return $this->nome;
    }

    function getNomeSimplificado() {
        return substr($this->nome, 0, 2);
    }

    function setGrupoPessoa($grupoPessoa) {
        $this->grupoPessoa = $grupoPessoa;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

}
