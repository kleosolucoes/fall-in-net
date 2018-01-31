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

  private $pagina;
  public function __construct() {

  }

  public function __invoke($pagina = '') {
    $this->setPagina($pagina);
    return $this->renderHtml();
  }

  public function renderHtml() {
    $html = '';
    if($this->view->token != -1){
      $extra = 'onclick="mudarPaginaComLoader(\'/adm'.$this->getPagina().'/-1\');"';
      $html .= $this->view->botao('<i class="icon wb-chevron-left-mini" aria-hidden="true"></i>', $extra,Botao::botaoMuitoPequenoSucesso);
      $html .=  '&nbsp;';
    }
    if($this->view->token != 0){
      $extra = 'onclick="mudarPaginaComLoader(\'/adm'.$this->getPagina().'\');"';
      $html .=  $this->view->botao('<i class="icon wb-home" aria-hidden="true"></i>', $extra,Botao::botaoMuitoPequenoSucesso);
      $html .=  '&nbsp;';      
    }
    if($this->view->token != 1){
      $extra = 'onclick="mudarPaginaComLoader(\'/adm'.$this->getPagina().'/1\');"';
      $html .=  $this->view->botao('<i class="icon wb-chevron-right-mini" aria-hidden="true"></i>', $extra,Botao::botaoMuitoPequenoSucesso);
    }
    return $html;
  }

  function getPagina() {
    return $this->pagina;
  }
  function setPagina($pagina) {
    $this->pagina = $pagina;
  }

}
