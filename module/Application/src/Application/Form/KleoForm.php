<?php

namespace Application\Form;

use Zend\Form\Element\Hidden;
use Zend\Form\Element\Csrf;
use Zend\Form\Form;

/**
 * Nome: KleoForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario base  
 *              
 */
class KleoForm extends Form {

  const inputNome = 'inputNome';
  const inputTelefone = 'inputTelefone';
  const inputGrupoPessoaTipo = 'inputGrupoPessoaTipo';
  const inputPonte = 'inputPonte';
  const inputEmail = 'inputEmail';
  const inputRepetirEmail = 'inputRepetirEmail';
  const inputSenha = 'inputSenha';
  const inputRepetirSenha = 'inputRepetirSenha';
  const inputDocumento = 'inputDocumento';
  const inputDia = 'inputDia';
  const inputMes = 'inputMes';
  const inputAno = 'inputAno';
  const inputSexo = 'inputSexo';
  const inputCodigoVerificador = 'inputCodigoVerificador';

  const inputId = 'inputId';
  const inputCSRF = 'inputCSRF';

  const stringClass = 'class';
  const stringClassFormControl = 'form-control round';
  const stringClassGuiFile = 'gui-file';
  const stringId = 'id';
  const stringPlaceholder = 'placeholder';
  const stringAction = 'action';
  const stringRequired = 'required';
  const stringDisabled = 'disabled';
  const stringValue = 'value';
  const stringOnblur = 'onblur';
  const stringValidacoesFormulario = 'validacoesFormulario(this);';
  const stringM = 'M';
  const stringF = 'F';

  const traducaoNome = 'Nome';
  const traducaoNomeCompleto = 'Nome Completo';
  const traducaoTelefone = 'Telefone';
  const traducaoEmail = 'Email';
  const traducaoRepetirEmail = 'Repita o Email';
  const traducaoSenha = 'Senha';
  const traducaoRepetirSenha = 'Repetir Senha';
  const traducaoSelecione = 'Selecione';
  const traducaoPonte = 'Ponte';
  const traducaoDocumento = 'C.P.F.';
  const traducaoDia = 'Dia';
  const traducaoMes = 'Mês';
  const traducaoAno = 'Ano';
  const traducaoSexo = 'Sexo';
  const traducaoMasculino = 'Masculino';
  const traducaoFeminino = 'Feminino';
  const traducaoCodigoVerificador = 'Código verificador';
  const traducaoCadastrarSenha = 'Cadastrar Senha';
  
  public function __construct($name = null) {

    parent::__construct($name);
    $this->setAttributes(array(
      'method' => 'post',
    ));

    $this->add(
      (new Hidden())
      ->setName(self::inputId)
      ->setAttributes([
      self::stringId => self::inputId,
    ])
    );

    $this->add(
      (new Csrf())
      ->setName('inputCSRF')
    );
  }
}