<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Controller\KleoController;
use Application\Form\KleoForm;
use Application\Model\Entity\TarefaTipo;

/**
 * Nome: PerformancePontes.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar tela de performance das pontes
 */
class PerformancePontes extends AbstractHelper {

  public function __construct() {

  }

  public function __invoke() {   
    return $this->renderHtml();
  }

  public function renderHtml() {
    $html = '';
    if(count($this->view->pontes) == 0 && $this->view->token == 0){ 
      $html .= '<div class="alert alert-icon alert-info w-full" role="alert">';
      $html .= '<i class="icon wb-alert" aria-hidden="true"></i>';
      $html .= '<p>Vamos começar cadastrando uma Ponte.</p>';
      $html .= '</div>';
    }
    if($this->view->pontes){
      $html .= '<div class="row">';
      foreach($this->view->pontes as $ponte){
        $pessoa = $ponte->getPessoa();
        $performance = 0;
        $valorCadaRealizacao = 0.0625;
        if($pessoa->getPonteProspectoProspectos()){
          foreach($pessoa->getPonteProspectoProspectos() as $ponteProspecto){
            $performance += $valorCadaRealizacao;
            $proscpecto = $ponteProspecto->getPonteProspectoProspecto();
            if($proscpecto->getTarefa()){
              $contadorDeLigacao = 0;
              $contadorDeMensagem = 0;
              foreach($proscpecto->getTarefa() as $tarefa){
                if($tarefa->getTarefaTipo()->getId() === TarefaTipo::LIGAR &&
                   $tarefa->getRealizada() == 'S'){
                  if($contadorDeLigacao === 0){
                    $performance += $valorCadaRealizacao;
                  } 
                  $contadorDeLigacao++;
                }

                if($tarefa->getTarefaTipo()->getId() === TarefaTipo::MENSAGEM &&
                   $tarefa->getRealizada() == 'S'){
                  if($contadorDeMensagem === 0){
                    $performance += $valorCadaRealizacao;
                  }
                  $contadorDeMensagem++;
                }
              }
            }
            if($proscpecto->getEventoFrequencia()){
              foreach($proscpecto->getEventoFrequencia() as $eventoFrequencia){
                if($eventoFrequencia->getFrequencia() == 'S'){
                  $performance += $valorCadaRealizacao;
                }  
              }
            }

            $html .= '<input type="hidden" id="contador_ligacao_'.$proscpecto->getId().'" value="'.$contadorDeLigacao.'">';
            $html .= '<input type="hidden" id="contador_mensagem_'.$proscpecto->getId().'" value="'.$contadorDeMensagem.'">';
          }
        }

        $performance *= 100;
        $html .= '<div class="col-6">';
        $html .= '<div class="card card-block p-30">';
        $html .= '<div class="counter counter-md text-left">';
        $html .= '<div class="counter-label text-uppercase mb-5">'.KleoForm::traducaoPonte.'</div>';
        $html .= '<div class="counter-number-group mb-10">';
        $html .= '<span class="counter-number">'.$ponte->getPessoa()->getNome().'</span>';
        $html .= '</div>';
        $html .= '<div class="counter-label">';
        $html .= '<div class="progress progress-lg mb-10">';
        $html .= '<div id="divBarraDeProgresso_'.$ponte->getPessoa()->getId().'" class="progress-bar 
                        progress-bar-info bg-blue-600" aria-valuenow="'.$performance.'" aria-valuemin="0"
                        aria-valuemax="100" style="width: '.$performance.'%" role="progressbar">'.$performance.'%</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
      }
      $html .= '</div>';
    }
    return $html;
  }

}
