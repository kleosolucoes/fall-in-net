<?php

namespace Application\Model\Entity;

/**
 * Nome: SolicitacaoTipo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela solicitacao_tipo 
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="solicitacao_tipo")
 */
class SolicitacaoTipo extends CircuitoEntity {

    const TRANSFERIR_LIDER_NA_PROPRIA_EQUIPE = 1;
    /**
     * @ORM\OneToMany(targetEntity="Solicitacao", mappedBy="solicitacaoTipo") 
     */
    protected $solicitacao;

    public function __construct() {
        $this->solicitacao = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    function getSolicitacao() {
        return $this->solicitacao;
    }

    function getNome() {
        return $this->nome;
    }

    function setSolicitacao($solicitacao) {
        $this->solicitacao = $solicitacao;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

}
