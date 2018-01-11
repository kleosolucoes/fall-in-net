<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: TemplateFormularioTopo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar o template do inicio da pagina de formularios
 */
class TemplateFormularioTopo extends AbstractHelper {

    protected $label;
    protected $id;

    public function __construct() {
        
    }

    public function __invoke($label, $id = '') {
        $this->setLabel($label);
        $this->setId($id);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<div class="mw1200 center-block">';
        $html .= '<div class="admin-form theme-primary">';
        if($this->getLabel() != ''){
            $html .= $this->view->tituloDaPagina($this->getLabel());
        }
        $html .= '<div id="' . $this->getId() . '" class="panel heading-border panel-primary">';
        return $html;
    }

    function getLabel() {
        return $this->label;
    }

    function setLabel($label) {
        $this->label = $label;
    }

    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
    }

}
