<?php

namespace Application\Model\Entity;

/**
 * Nome: PonteProspecto.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela ponte_prospecto
 */
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="ponte_prospecto")
 */
class PonteProspecto extends KleoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="ponteProspectosProspectos")
     * @ORM\JoinColumn(name="ponte_id", referencedColumnName="id")
     */
    private $ponteProspectoPonte;

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="ponteProspectoPonte")
     * @ORM\JoinColumn(name="prospecto_id", referencedColumnName="id")
     */
    private $ponteProspectoProspecto;

    /** @ORM\Column(type="integer") */
    protected $ponte_id;

    /** @ORM\Column(type="integer") */
    protected $prospecto_id;

    function getPonte_id() {
        return $this->ponte_id;
    }

    function getProspecto_id() {
        return $this->prospecto_id;
    }

    function setPonte_id($ponte_id) {
        $this->ponte_id = $ponte_id;
    }

    function setProspecto_id($prospecto_id) {
        $this->prospecto_id = $prospecto_id;
    }

    /**
     * @return Pessoa
     */
    function getPonteProspectoPonte() {
        return $this->ponteProspectoPonte;
    }

    function setPonteProspectoPonte($ponteProspectoPonte) {
        $this->ponteProspectoPonte = $ponteProspectoPonte;
    }

    /**
     * @return Pessoa
     */
    function getPonteProspectoProspecto() {
        return $this->ponteProspectoProspecto;
    }

    function setPonteProspectoProspecto($ponteProspectoProspecto) {
        $this->ponteProspectoProspecto = $ponteProspectoProspecto;
    }

}
