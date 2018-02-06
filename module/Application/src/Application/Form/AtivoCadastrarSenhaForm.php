<?php

namespace Application\Form;

use Zend\Form\Element\Password;
use Application\Model\Entity\Pessoa;
use Zend\Form\Element\Text;
use Zend\Form\Element\Number;
use Zend\Form\Element\Email;
use Zend\Form\Element\Select;
use Zend\Form\Element\Checkbox;

/**
 * Nome: ResponsavelSenhaAtualizacaoForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario de atualizacao de senha do ativo
 *              
 */
class AtivoCadastrarSenhaForm extends KleoForm {

  public function __construct($name = null, Pessoa $pessoa = null) {
    parent::__construct($name);

    if($pessoa){
      $inputId = $this->get(self::inputId);
      $inputId->setValue($pessoa->getId());
    }
    
    $this->add(
      (new Password())
      ->setName(self::inputSenha)
      ->setAttributes([
        self::stringClass => self::stringClassFormControl,
        self::stringId => self::inputSenha,
        self::stringRequired => self::stringRequired,
        self::stringOnblur => self::stringValidacoesFormulario,
      ])
    );

    $this->add(
      (new Password())
      ->setName(self::inputRepetirSenha)
      ->setAttributes([
        self::stringClass => self::stringClassFormControl,
        self::stringId => self::inputRepetirSenha,
        self::stringRequired => self::stringRequired,
        self::stringOnblur => self::stringValidacoesFormulario,
      ])
    );
    
  }
}