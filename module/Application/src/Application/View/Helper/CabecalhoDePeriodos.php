<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\View\Helper\Botao;

/**
 * Nome: CabecalhoDePeriodos.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar os periodos
 */
class CabecalhoDePeriodos extends AbstractHelper {

  public function __construct() {

  }

  public function __invoke() {   
    return $this->renderHtml();
  }

  public function renderHtml() {
    $html = '';
    if($this->view->token != -1){
      $extra = 'onclick="mudarPaginaComLoader(\'/adm/-1\');"';
      $html .= $this->view->botao('<i class="icon wb-chevron-left-mini" aria-hidden="true"></i>', $extra,Botao::botaoMuitoPequenoSucesso);
      $html .=  '&nbsp;';
    }
    if($this->view->token != 0){
      $extra = 'onclick="mudarPaginaComLoader(\'/adm\');"';
      $html .=  $this->view->botao('<i class="icon wb-home" aria-hidden="true"></i>', $extra,Botao::botaoMuitoPequenoSucesso);
      $html .=  '&nbsp;';      
    }
    if($this->view->token != 1){
      $extra = 'onclick="mudarPaginaComLoader(\'/adm/1\');"';
      $html .=  $this->view->botao('<i class="icon wb-chevron-right-mini" aria-hidden="true"></i>', $extra,Botao::botaoMuitoPequenoSucesso);
    }
    return $html;
  }


}
