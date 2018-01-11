<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: CabecalhoDeCiclos.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar o ciclo atual com link para os proximos
 */
class CabecalhoDeCiclos extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

        $mesSelecionado = Funcoes::mesPorAbaSelecionada($this->view->abaSelecionada);
        $anoSelecionado = Funcoes::anoPorAbaSelecionada($this->view->abaSelecionada);
        $urlBase = $this->view->url(Constantes::$ROUTE_LANCAMENTO);
        $urlBaseCiclo = $urlBase . '/' . $this->view->abaSelecionada . '_';

        $traducaoPeriodo = $this->view->translate(Constantes::$TRADUCAO_PERIODO);
        $urlCicloAnterior = $urlBaseCiclo . ($this->view->cicloSelecionado - 1);
        $urlCicloPosterior = $urlBaseCiclo . ($this->view->cicloSelecionado + 1);

        $html .= '<div class="center-block text-center" style="padding:10px;"> ';
        $html .= '<a href="' . $urlCicloAnterior . '"><button class="btn btn-default btn-sm"><i class="fa fa-angle-double-left"></i></button></a>&nbsp;';
        $html .= $this->view->translate(Constantes::$TRADUCAO_PERIODO) . '&nbsp;-&nbsp;' . Funcoes::periodoCicloMesAno($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado);
        $html .= '&nbsp;<a href="' . $urlCicloPosterior . '"><button class="btn btn-default btn-sm"><i class="fa fa-angle-double-right"></i></button></a>';
        $html .= '</div>';
        return $html;
    }

}
