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
class GrupoPessoa extends CircuitoEntity {

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
     * @ORM\JoinColumn(name="tipo_id", referencedColumnName="id")
     */
    private $grupoPessoaTipo;

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    /** @ORM\Column(type="integer") */
    protected $tipo_id;

    /** @ORM\Column(type="string") */
    protected $transferido;

    /** @ORM\Column(type="string") */
    protected $nucleo_perfeito;

    /**
     * Verificar se a data de inativação está nula
     * @return boolean
     */
    public function verificarSeEstaAtivo() {
        $resposta = false;
        if (is_null($this->getData_inativacao())) {
            $resposta = true;
        }
        return $resposta;
    }

    /**
     * Verificar se a data de inativação foi no mes informado
     * @return boolean
     */
    public function verificarSeInativacaoFoiNoMesInformado($mes, $ano) {
        $resposta = false;
        if ($this->getData_inativacaoMes() == $mes && $this->getData_inativacaoAno() == $ano) {
            $resposta = true;
        }
        return $resposta;
    }

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

    function getTransferido() {
        return $this->transferido;
    }

    function setTransferido($transferido) {
        $this->transferido = $transferido;
    }

    function getNucleo_perfeito() {
        return $this->nucleo_perfeito;
    }

    function setNucleo_perfeito($nucleo_perfeito) {
        $this->nucleo_perfeito = $nucleo_perfeito;
    }

    function getTipo_id() {
        return $this->tipo_id;
    }

    function setTipo_id($tipo_id) {
        $this->tipo_id = $tipo_id;
    }

}
