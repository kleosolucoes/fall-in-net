<?php
namespace Application\View\Helper;
use Zend\View\Helper\AbstractHelper;
/**
 * Nome: Botao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar um botao
 */
class Botao extends AbstractHelper {
  private $label;
  private $extra;
  private $tipoBotao;
  private $posicao;
  const botaoImportante = 1;
  const botaoMenorImportancia = 2;
  const botaoSucesso = 3;
  const botaoPequenoImportante = 4;
  const botaoPequenoMenosImportante = 5;
  const botaoMuitoPequenoImportante = 6;
  const botaoMuitoPequenoMenosImportante = 7;
  const botaoMuitoPequenoSucesso = 9;
  const botaoPerigoso = 8;
  const posicaoADireita = 1;
  const posicaoAEsquerda = 2;
  const posicaoAoCentro = 3;
  public function __construct() {

  }
  public function __invoke($label, $extra = '', $tipoBotao = Botao::botaoImportante, $posicao = Botao::posicaoADireita) {
    $this->setLabel($label);
    $this->setExtra($extra);
    $this->setTipoBotao($tipoBotao);
    $this->setPosicao($posicao);
    return $this->renderHtml();
  }
  public function renderHtml() {
    $html = '';
    $classBotao = 'primary';
    $posicaoBotao = 'pull-right';
    if ($this->getTipoBotao() === Botao::botaoMenorImportancia) {
      $classBotao = 'default';
    }
    if ($this->getTipoBotao() === Botao::botaoSucesso) {
      $classBotao = 'success';
    }
    if ($this->getTipoBotao() === Botao::botaoPerigoso) {
      $classBotao = 'danger';
    }
    if ($this->getTipoBotao() === Botao::botaoPequenoImportante) {
      $classBotao = 'sm btn-' . $classBotao;
    }
    if ($this->getTipoBotao() === Botao::botaoPequenoMenosImportante) {
      $classBotao = 'sm btn-default';
    }
    if ($this->getTipoBotao() === Botao::botaoMuitoPequenoImportante) {
      $classBotao = 'xs btn-' . $classBotao;
    }
    if ($this->getTipoBotao() === Botao::botaoMuitoPequenoMenosImportante) {
      $classBotao = 'xs btn-default';
    }
    if ($this->getTipoBotao() === Botao::botaoMuitoPequenoSucesso) {
      $classBotao = 'xs btn-success';
    }
    if ($this->getPosicao() === Botao::posicaoAEsquerda) {
      $posicaoBotao = 'pull-left';
    }
    if ($this->getPosicao() === Botao::posicaoAoCentro) {
      $posicaoBotao = '';
    }
    $html .= '<button type="button" ' . $this->getExtra() . ' class="btn ladda-button btn-' . $classBotao . ' ' . $posicaoBotao . ' ml10" data-style="zoom-in">';
    $html .= '<span class="ladda-label">';
    $html .= $this->view->translate($this->getLabel());
    $html .= '</span>';
    $html .= '</button>';
    return $html;
  }
  function getLabel() {
    return $this->label;
  }
  function getExtra() {
    return $this->extra;
  }
  function setLabel($label) {
    $this->label = $label;
    return $this;
  }
  function setExtra($extra) {
    $this->extra = $extra;
    return $this;
  }
  function getTipoBotao() {
    return $this->tipoBotao;
  }
  function setTipoBotao($tipoBotao) {
    $this->tipoBotao = $tipoBotao;
    return $this;
  }
  function getPosicao() {
    return $this->posicao;
  }
  function setPosicao($posicao) {
    $this->posicao = $posicao;
  }
}