<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Controller\KleoController;

/**
 * Nome: Splash.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar uma tela de splash ou loader
 */
class Splash extends AbstractHelper {

  public function __construct() {

  }

  public function __invoke() {   
    return $this->renderHtml();
  }

  public function renderHtml() {
    $html = '';
    $html .= '<!-- Simple splash screen-->';
    $html .= '<div class="splash">';
    $html .= '<div class="color-line"></div>';
    $html .= '<div class="splash-title">';
    $html .= '<img class="brand-img" src="/img/logoursa.png" alt="...">';
    $html .= '<div class="spinner">'; 
    $html .= '<div class="rect1"></div>'; 
    $html .= '<div class="rect2"></div>'; 
    $html .= '<div class="rect3"></div>'; 
    $html .= '<div class="rect4"></div>'; 
    $html .= '<div class="rect5"></div>'; 
    $html .= '</div>'; 
    $html .= '</div>';
    $html .= '</div>';
    return $html;
  }

}
