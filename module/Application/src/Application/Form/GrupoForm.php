<?php

namespace Application\Form;

use Zend\Form\Element\Email;
use Zend\Form\Element\Number;
use Zend\Form\Element\Text;
use Zend\Form\Element\Select;
use Zend\Form\Form;

/**
 * Nome: GrupoForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar grupo
 */
class GrupoForm extends KleoForm {


  /**
     * Contrutor
     * @param String $name
     */
  public function __construct($name = null) {
    parent::__construct($name);

    $this->add(
      (new Text())
      ->setName(self::inputNome)
      ->setAttributes([
        self::stringClass => self::stringClassFormControl,
        self::stringId => self::inputNome,
        self::stringRequired => self::stringRequired,
        self::stringOnblur => self::stringValidacoesFormulario,
        self::stringPlaceholder => self::traducaoNome      
      ])
    );

    $this->add(
      (new Number())
      ->setName(self::inputDocumento)
      ->setAttributes([
        self::stringClass => self::stringClassFormControl,
        self::stringId => self::inputDocumento,
        self::stringRequired => self::stringRequired,
        self::stringOnblur => self::stringValidacoesFormulario,
        self::stringPlaceholder => self::traducaoDocumento      
      ])
    );

    /* Dia da data de nascimento */
    $arrayDiaDataNascimento = array();
    $arrayDiaDataNascimento[0] = self::traducaoSelecione;
    for ($indiceDiaDoMes = 1; $indiceDiaDoMes <= 31; $indiceDiaDoMes++) {
      $numeroAjustado = str_pad($indiceDiaDoMes, 2, 0, STR_PAD_LEFT);
      $arrayDiaDataNascimento[$indiceDiaDoMes] = $numeroAjustado;
    }
    $inputSelectDiaDataNascimento = new Select();
    $inputSelectDiaDataNascimento->setName(self::inputDia);
    $inputSelectDiaDataNascimento->setAttributes(array(  
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputDia,
      self::stringRequired => self::stringRequired,
      self::stringOnblur => self::stringValidacoesFormulario,
    ));
    $inputSelectDiaDataNascimento->setValueOptions($arrayDiaDataNascimento);
    $this->add($inputSelectDiaDataNascimento);

    /* Mês da data de nascimento */
    $arrayMesDataNascimento = array();
    $arrayMesDataNascimento[0] = self::traducaoSelecione;
    for ($indiceMesNoAno = 1; $indiceMesNoAno <= 12; $indiceMesNoAno++) {
      $numeroAjustado = str_pad($indiceMesNoAno, 2, 0, STR_PAD_LEFT);
      $arrayMesDataNascimento[$indiceMesNoAno] = $numeroAjustado;
    }
    $inputSelectMesDataNascimento = new Select();
    $inputSelectMesDataNascimento->setName(self::inputMes);
    $inputSelectMesDataNascimento->setAttributes(array(
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputMes,
      self::stringRequired => self::stringRequired,
      self::stringOnblur => self::stringValidacoesFormulario,
    ));
    $inputSelectMesDataNascimento->setValueOptions($arrayMesDataNascimento);
    $this->add($inputSelectMesDataNascimento);

    /* Ano da data de nascimento */
    $arrayAnoDataNascimento = array();
    $arrayAnoDataNascimento[0] = self::traducaoSelecione;
    $anoAtual = date('Y');
    for ($indiceAno = $anoAtual; $indiceAno >= ($anoAtual - 100); $indiceAno--) {
      $arrayAnoDataNascimento[$indiceAno] = $indiceAno;
    }
    $inputSelectAnoDataNascimento = new Select();
    $inputSelectAnoDataNascimento->setName(self::inputAno);
    $inputSelectAnoDataNascimento->setAttributes(array(
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputAno,
      self::stringRequired => self::stringRequired,
      self::stringOnblur => self::stringValidacoesFormulario,
    ));
    $inputSelectAnoDataNascimento->setValueOptions($arrayAnoDataNascimento);
    $this->add($inputSelectAnoDataNascimento);

    /* Ano da data de nascimento */
    $arraySexo = array();
    $arraySexo[0] = self::traducaoSelecione;
    $arraySexo[self::stringM] = self::traducaoMasculino;
    $arraySexo[self::stringF] = self::traducaoFeminino;

    $inputSelectSexo = new Select();
    $inputSelectSexo->setName(self::inputSexo);
    $inputSelectSexo->setAttributes(array(
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputSexo,
      self::stringRequired => self::stringRequired,
      self::stringOnblur => self::stringValidacoesFormulario,
    ));
    $inputSelectSexo->setValueOptions($arraySexo);
    $this->add($inputSelectSexo);

    /* Email */
    $this->add(
      (new Email())
      ->setName(self::inputEmail)
      ->setAttributes([
        self::stringClass => self::stringClassFormControl,
        self::stringId => self::inputEmail,   
        self::stringOnblur => self::stringValidacoesFormulario,
        self::stringPlaceholder => self::traducaoEmail,
      ])
    );

    /* Repetir Email */
    $this->add(
      (new Email())
      ->setName(self::inputRepetirEmail)
      ->setAttributes([
        self::stringClass => self::stringClassFormControl,
        self::stringId => self::inputRepetirEmail,   
        self::stringOnblur => self::stringValidacoesFormulario,
        self::stringPlaceholder => self::traducaoRepetirEmail,
      ])
    );
  }

}