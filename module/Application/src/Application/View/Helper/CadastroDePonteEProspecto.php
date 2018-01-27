<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Controller\KleoController;
use Application\View\Helper\Botao;
use Application\Model\Entity\GrupoPessoaTipo;
use Application\Form\KleoForm;


/**
 * Nome: CadastroDePonteEProspecto.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar uma tela de cadastro de ponte e prospecto
 */
class CadastroDePonteEProspecto extends AbstractHelper {

  public function __construct() {

  }

  public function __invoke() {   
    return $this->renderHtml();
  }

  public function renderHtml() {
    $html = '';

    if($this->view->token == 0){
      $html .= '<div id="divSiteAction" class="site-action" data-plugin="actionBtn">';
      $html .= '<button onclick="$(\'#divSiteAction\').toggleClass(\'active\');" type="button" class="site-action-toggle btn-raised btn btn-success btn-floating">';
      $html .= '<i class="front-icon wb-plus animation-scale-up" aria-hidden="true"></i>';
      $html .= '<i class="back-icon wb-close animation-scale-up" aria-hidden="true"></i>';
      $html .= '</button>';
      $html .= '<div class="site-action-buttons">';
      if(count($this->view->pontes) < 2){ 
        $html .= '<button onclick="selecionarPonteProspecto(' . GrupoPessoaTipo::PONTE . ');" type="button" data-toggle="modal" data-target="#addStageFrom" class="btn-raised btn btn-success btn-floating animation-slide-bottom">';
        $html .= '<i class="icon wb-user" aria-hidden="true"></i>';
        $html .= '</button>';
      } 
      if(count($this->view->pontes) > 0){ 
        $html .= '<button onclick="selecionarPonteProspecto(' . GrupoPessoaTipo::PROSPECTO . ');" type="button" data-toggle="modal" data-target="#addStageFrom" class="btn-raised btn btn-success btn-floating animation-slide-bottom">';
        $html .= '<i class="icon wb-users" aria-hidden="true"></i>';
        $html .= '</button>';
      } 
      $html .= '</div>';
      $html .= '</div>';
    }
    $html .= '<div class="modal fade" id="addStageFrom" aria-hidden="true" aria-labelledby="addStageFrom"
                 role="dialog" tabindex="-1">';
    $html .= '<div class="modal-dialog modal-simple">';
    $html .= '<div class="modal-content">';
    $html .= '<div class="modal-header">';
    $html .= '<button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>';
    $html .= '<h4 id="modalTitle" class="modal-title"></h4>';
    $html .= '</div>';

    $fimIndice = 2;
    if(count($this->view->pontes) == 0){
      $fimIndice = 1;
    }
    for($indice = 1;$indice <= $fimIndice; $indice++){
      if($indice === 1){
        $formulario = $this->view->formularioPonte;
        $nomeFormulario = 'Ponte';
      }
      if($indice === 2){
        $formulario = $this->view->formularioProspecto;
        $nomeFormulario = 'Prospecto';
      }
      $html .= '<div id="divFormulario'.$nomeFormulario.'">';
      $formulario->prepare();
      $formulario->setAttribute(KleoForm::stringAction, 'admPonteProspectoFinalizar');
      $html .=  $this->view->form()->openTag($formulario);
      $html .= $this->view->formHidden($formulario->get(KleoForm::inputCSRF));
      $html .= $this->view->formHidden($formulario->get(KleoForm::inputGrupoPessoaTipo));
      $html .= '<div class="modal-body">';
      if($indice === 2){
        $html .= $this->view->inputFormulario(KleoForm::traducaoPonte, $formulario->get(KleoForm::inputPonte)); 
      }
      $html .= $this->view->inputFormulario(KleoForm::traducaoNome, $formulario->get(KleoForm::inputNome.$nomeFormulario)); 
      $html .= $this->view->inputFormulario(KleoForm::traducaoTelefone, $formulario->get(KleoForm::inputTelefone.$nomeFormulario)); 
      $html .= '</div>';
      $html .= '<div class="modal-footer text-left">';
      $html .= $this->view->botao('Cadastrar', $this->view->funcaoOnClick('submeterFormulario(this.form)')); 
      $html .= $this->view->botao('Cancelar', 'data-dismiss="modal"',Botao::botaoMenorImportancia); 
      $html .= '</div>';
      $html .= $this->view->form()->closeTag();
      $html .= '</div>';
    }

    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
  }

}
