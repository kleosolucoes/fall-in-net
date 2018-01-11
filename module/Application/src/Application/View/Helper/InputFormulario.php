<?php

namespace Application\View\Helper;

use Cadastro\Form\ConstantesForm;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: InputFormulario.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar um input com labels
 */
class InputFormulario extends AbstractHelper {

    protected $traducao;
    protected $form;
    protected $idInput;
    protected $icone;
    protected $tipo;
    protected $funcao;

    public function __construct() {
        
    }

    public function __invoke($traducao, $form, $idInput, $icone, $tipo = 0, $funcao = '') {
        $this->setTraducao($traducao);
        $this->setForm($form);
        $this->setIdInput($idInput);
        $this->setIcone($icone);
        $this->setTipo($tipo);
        $this->setFuncao($funcao);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $input = $this->getForm()->get($this->getIdInput());
        if ($this->getTipo() == 2) {
            $valor = '';
            if (!empty($input->getValue())) {
                $valor = $input->getValue();
            }
            $html .= '<input type="hidden" id="hidden' . $this->getIdInput() . '" name="hidden' . $this->getIdInput() . '" value="' . $valor . '"/>';
        }
//        if ($this->getIdInput() != ConstantesForm::$FORM_CPF) {
//            $html .= '<label for=' . $this->getTraducao() . ' class="field-label">';
//            $html .= $this->view->translate($this->getTraducao());
//            if ($this->getIdInput() == ConstantesForm::$FORM_CEP_LOGRADOURO) {
//                $html .= $this->view->translate(ConstantesForm::$TRADUCAO_CEP_LOGRADOURO_SITE_CORREIOS);
//            }
//            $html .= '</label>';
//        }
        $html .= '<label for="' . $this->getTraducao() . '" class="field prepend-icon">';

        /* Desabilitar */
        if ($this->getTipo() == 2) {
            $input->setAttribute(ConstantesForm::$FORM_DISABLED, ConstantesForm::$FORM_DISABLED);
        }
        $html .= $this->view->formInput($input);
        $html .= '<label for="' . $this->getTraducao() . '" class="field-icon">';
        $html .= '<i class="fa ' . $this->getIcone() . '"></i>';
        $html .= '</label>';

        return $html;
    }

    function getTraducao() {
        return $this->traducao;
    }

    function getForm() {
        return $this->form;
    }

    function getIdInput() {
        return $this->idInput;
    }

    function getIcone() {
        return $this->icone;
    }

    function setTraducao($traducao) {
        $this->traducao = $traducao;
    }

    function setForm($form) {
        $this->form = $form;
    }

    function setIdInput($idInput) {
        $this->idInput = $idInput;
    }

    function setIcone($icone) {
        $this->icone = $icone;
    }

    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function getFuncao() {
        return $this->funcao;
    }

    function setFuncao($funcao) {
        $this->funcao = $funcao;
    }

}
