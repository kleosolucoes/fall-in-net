<?php

namespace Application\Form;

use Zend\Form\Element\Text;
use Zend\Form\Element\Number;
use Zend\Form\Element\Hidden;
use Application\Model\Entity\GrupoPessoaTipo;

/**
 * Nome: PonteForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario de cadastro de ponte  
 *              
 */
class PonteForm extends KleoForm {

  public function __construct($name = null) {
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
        self::stringValue => GrupoPessoaTipo::PONTE,
      ])
    );
  }
}