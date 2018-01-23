<?php

namespace Application\Form;

use Zend\Form\Element\Text;
use Zend\Form\Element\Number;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Application\Model\Entity\GrupoPessoaTipo;

/**
 * Nome: ProspectoForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario de cadastro de prospectos  
 *              
 */
class ProspectoForm extends KleoForm {

  public function __construct($name = null, $pontes = null) {
    parent::__construct($name);

    $this->add(
      (new Text())
      ->setName(self::inputNome.$name)
      ->setAttributes([
        self::stringClass => self::stringClassFormControl,
        self::stringId => self::inputNome.$name,
        self::stringRequired => self::stringRequired,
        self::stringOnblur => self::stringValidacoesFormulario,
        self::stringPlaceholder => self::traducaoNome      
      ])
    );

    $this->add(
      (new Number())
      ->setName(self::inputTelefone.$name)
      ->setAttributes([
        self::stringClass => self::stringClassFormControl,
        self::stringId => self::inputTelefone.$name,
        self::stringRequired => self::stringRequired,
        self::stringOnblur => self::stringValidacoesFormulario,
        self::stringPlaceholder => self::traducaoTelefone .' DDD + Numero'
      ])
    );
    $this->add(
      (new Hidden())
      ->setName(self::inputGrupoPessoaTipo)
      ->setAttributes([
        self::stringId => self::inputGrupoPessoaTipo,
        self::stringValue => GrupoPessoaTipo::PROSPECTO,
      ])
    );

    $inputSelectPonte = new Select();
    $inputSelectPonte->setName(self::inputPonte);
    $inputSelectPonte->setAttributes(array(
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputPonte,
      self::stringRequired => self::stringRequired,
    ));
    $inputSelectPonte->setEmptyOption(self::traducaoSelecione);
    $this->add($inputSelectPonte);
    $this->setarPontes($pontes);
  }

  public function setarPontes($pontes) {
    $arrayPontes = [];
    if ($pontes) {
      foreach ($pontes as $ponte) {
        $arrayPontes[$ponte->getPessoa()->getId()] = $ponte->getPessoa()->getNome();
      }
    }
    $inpuLista = $this->get(self::inputPonte);
    $inpuLista->setValueOptions($arrayPontes);
  }
}