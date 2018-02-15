<?php

namespace Application\Form;

use Zend\Form\Element\Email;
use Zend\Form\Element\Number;
use Zend\Form\Element\Text;
use Zend\Form\Element\Select;
use Zend\Form\Form;

/**
 * Nome: AtivoAtualizacaoForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para atualizar um ativo
 */
class AtivoAtualizacaoForm extends KleoForm {


  /**
     * Contrutor
     * @param String $name
     */
  public function __construct($name = null, $idPessoa = null) {
    parent::__construct($name);

    if($idPessoa){
      $inputId = $this->get(self::inputId);
      $inputId->setValue($idPessoa);
    }

    $this->add(
      (new Number())
      ->setName(self::inputTelefone)
      ->setAttributes([
        self::stringClass => self::stringClassFormControl,
        self::stringId => self::inputTelefone,
        self::stringRequired => self::stringRequired,
        self::stringOnblur => self::stringValidacoesFormulario,
        self::stringPlaceholder => self::traducaoTelefone .' DDD + Numero'
      ])
    );

    $this->add(
      (new Number())
      ->setName(self::inputCodigoVerificador)
      ->setAttributes([
        self::stringClass => self::stringClassFormControl,
        self::stringId => self::inputCodigoVerificador,
        self::stringRequired => self::stringRequired,
        self::stringOnblur => self::stringValidacoesFormulario,
        self::stringPlaceholder => self::traducaoCodigoVerificador
      ])
    );

  }

}