<?php

namespace Application\Model\Entity;

/**
 * Nome: GrupoPessoa.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela grupo_pessoa
 * 1 - VISITANTE
 * 2 - CONSOLIDACAO
 * 3 - MEMBRO
 */
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="grupo_pessoa")
 */
class GrupoPessoa extends KleoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="grupoPessoa")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="grupoPessoa")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

    /**
     * @ORM\ManyToOne(targetEntity="GrupoPessoaTipo", inversedBy="grupoPessoa")
     * @ORM\JoinColumn(name="grupo_pessoa_tipo_id", referencedColumnName="id")
     */
    private $grupoPessoaTipo;

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    /** @ORM\Column(type="integer") */
    protected $grupo_pessoa_tipo_id;

    /**
     * Retorna a pessoa
     * @return Pessoa
     */
    function getPessoa() {
        return $this->pessoa;
    }

    /**
     * Retorna o grupo da responsabilidade
     * @return Grupo
     */
    function getGrupo() {
        return $this->grupo;
    }

    function getPessoa_id() {
        return $this->pessoa_id;
    }

    function getGrupo_id() {
        return $this->grupo_id;
    }

    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setPessoa_id($pessoa_id) {
        $this->pessoa_id = $pessoa_id;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }

    /**
     * Retorna grupo pessoa tipo
     * @return GrupoPessoaTipo
     */
    function getGrupoPessoaTipo() {
        return $this->grupoPessoaTipo;
    }

    function setGrupoPessoaTipo($grupoPessoaTipo) {
        $this->grupoPessoaTipo = $grupoPessoaTipo;
    }

    function getGrupo_pessoa_tipo_id() {
        return $this->grupo_pessoa_tipo_id;
    }

    function setGrupo_pessoa_tipo_id($grupo_pessoa_tipo_id) {
        $this->grupo_pessoa_tipo_id = $grupo_pessoa_tipo_id;
    }

}
