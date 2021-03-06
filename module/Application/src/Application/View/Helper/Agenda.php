<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Controller\KleoController;
use Application\Model\Entity\TarefaTipo;
use Application\Model\Entity\Tarefa;
use Application\Model\Entity\Evento;
use Application\Model\Entity\GrupoPessoaTipo;
use Application\Model\Entity\FatoCiclo;

/**
 * Nome: Agenda.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar uma tabela de agenda
 */
class Agenda extends AbstractHelper {

  public function __construct() {

  }

  public function __invoke() {   
    return $this->renderHtml();
  }

  public function renderHtml() {
    $html = '';

    $html .= '<table class="table table-sm">';
    $html .= '<tbody>';

    $ocorreuEvento = false;
    for($indiceDias = $this->view->inicioDoCiclo;$indiceDias <= $this->view->fimDoCiclo;$indiceDias++){
      if(date('d', strtotime('now +'.$indiceDias.' days')) == 1){
        $html .= '<tr class="bg-primary text-center"><td colspan="2"><p>'.KleoController::mesPorExtenso(date('m', strtotime('now +'.$indiceDias.' days')),1).'</p></td></tr>';       
      }

      $idAncora = '';
      if($indiceDias === 0){
        $idAncora = 'ancora';
      }

      $html .= '<tr id="' . $idAncora . '">';
      $html .= '<td>';
      $html .= '<p>';

      $html .= KleoController::diaDaSemanaPorDia(date('N', strtotime('now +'.$indiceDias.' days'))); 
      $html .= '<br />' . date('d', strtotime('now +'.$indiceDias.' days')); 

      $html .= '</p>';
      $html .= '</td>';
      $html .= '<td>';

      if($this->view->agenda[$indiceDias][0] instanceof Tarefa || $this->view->agenda[$indiceDias][0] instanceof Evento){
        foreach($this->view->agenda[$indiceDias] as $elemento){
          if($elemento instanceof Tarefa){
            if($elemento->getTarefaTipo()->getId() === TarefaTipo::LIGAR){
              $corTarefa = 'info';
            }
            if($elemento->getTarefaTipo()->getId() === TarefaTipo::MENSAGEM){
              $corTarefa = 'success';
            }
            $iconeTarefaRealizada = 'down';
            $corTarefaRealizada = Botao::botaoMuitoPequenoMenosImportante;
            if($elemento->getRealizada() === "S"){
              $iconeTarefaRealizada = 'up';
              $corTarefaRealizada = Botao::botaoMuitoPequenoImportante;
            }

            $html .= '<div class="row px-5">';
            $html .= '<div class="alert alert-' . $corTarefa . ' w-full" role="alert">';

            $html .= $elemento->getTarefaTipo()->getNome() . ' para ' . $elemento->getPessoa()->getNome(); 
            if(count($elemento->getPessoa()->getPonteProspectoPonte())>0){
              $html .= ' prospecto de '.$elemento->getPessoa()->getPonteProspectoPonte()[0]->getPonteProspectoPonte()->getNome();
            }
            $html .= '<p>';
            if($elemento->getTarefaTipo()->getId() === TarefaTipo::LIGAR){
              $label = $elemento->getPessoa()->getTelefone();
              $extra = 'onclick="clicarAcao('.FatoCiclo::CLIQUE_LIGACAO.', \''.$elemento->getPessoa()->getTelefone().'\', 0);"';
              $html .= '<div class="float-left">';
              $html .= $this->view->botao($label, $extra, Botao::botaoMuitoPequenoImportante);
              $html .= '</div>';
            }
            if($elemento->getTarefaTipo()->getId() === TarefaTipo::MENSAGEM){
              $label = 'MSG Whats';
              $extra = 'onclick="clicarAcao('.FatoCiclo::CLIQUE_MENSAGEM.', \''.$elemento->getPessoa()->getTelefone().'\', \''.$elemento->getPessoa()->getNome().'\');"';
              $html .= '<div class="float-left">';
              $html .= $this->view->botao($label, $extra, Botao::botaoMuitoPequenoImportante);
              $html .= '</div>';
            }
            $idPonte = 0;
            if(count($elemento->getPessoa()->getPonteProspectoPonte())>0){
              $idPonte = $elemento->getPessoa()->getPonteProspectoPonte()[0]->getPonteProspectoPonte()->getId();
            }
            $label = '<i class="icon fa-thumbs-' . $iconeTarefaRealizada . '" aria-hidden="true"></i>';
            $extra = 'id="botao_' . $elemento->getId() . '" onclick="mudarFrequencia(1, ' . $elemento->getId() . ','.$elemento->getTarefaTipo()->getId().','.$elemento->getPessoa()->getId().',0,'.$idPonte.');"';
            $html .= '<div class="float-right">';
            $html .= $this->view->botao($label, $extra, $corTarefaRealizada);
            $html .= '</div>';
            $html .= '</p>';

            $html .= '</div>';
            $html .= '</div>';

          }
          if($elemento instanceof Evento){
            $ocorreuEvento = true;
            $html .= '<div class="row px-5">';
            $html .= '<div class="alert alert-danger w-full" role="alert">';

            $html .= '<div class="p-5 row">';
            $html .= $elemento->getNome(); 
            $html .= '</div>';
            if($this->view->grupoPessoas){
              foreach($this->view->grupoPessoas as $grupoPessoa){
                $mostrar = true;
                $diaRealDoEvento = date('Y-m-d', strtotime('now +'.$indiceDias.' days'));
                if($grupoPessoa->getData_criacaoFormatoBandoDeDados() >= $diaRealDoEvento){
                  $mostrar = false;
                }                    
                if($mostrar){
                  $html .= '<div class="mt-5 row">';
                  $html .= '<div class="col-8">';
                  $html .= $grupoPessoa->getPessoa()->getNome();
                  if(count($grupoPessoa->getPessoa()->getPonteProspectoPonte())>0){
                    $html .= ' prospecto de '.$grupoPessoa->getPessoa()->getPonteProspectoPonte()[0]->getPonteProspectoPonte()->getNome();
                  }
                  $html .= '</div>';
                  $eventosFiltrado = $grupoPessoa->getPessoa()->getEventoFrequenciaFiltradoPorEventoEDia($elemento->getId(), $diaRealDoEvento);
                  $iconeTarefaRealizada = 'down';
                  $corTarefaRealizada = Botao::botaoMuitoPequenoMenosImportante;
                  if($eventosFiltrado){
                    if($eventosFiltrado->getFrequencia() === "S"){
                      $iconeTarefaRealizada = 'up';
                      $corTarefaRealizada = Botao::botaoMuitoPequenoImportante;
                    }  
                  }
                  $idPonte = 0;
                  if(count($grupoPessoa->getPessoa()->getPonteProspectoPonte())>0){
                    $idPonte = $grupoPessoa->getPessoa()->getPonteProspectoPonte()[0]->getPonteProspectoPonte()->getId();
                  }
                  $label = '<i class="icon fa-thumbs-' . $iconeTarefaRealizada . '" aria-hidden="true"></i>';
                  $extra = 'id="botao_' . $elemento->getId().'_'.$grupoPessoa->getPessoa()->getId() .'" onclick="mudarFrequencia(2,0, ' . $elemento->getId() . ',' . $grupoPessoa->getPessoa()->getId() . ', \'' .  $diaRealDoEvento . '\'' . ', '.$idPonte.');"';
                  $html .= '<div class="col-4">';
                  $html .= $this->view->botao($label, $extra, $corTarefaRealizada);
                  $html .= '</div>';
                  $html .= '</div>';
                }

              }
            }

            $html .= '</div>';
            $html .= '</div>';
          }
        }
        /* fim foreach */
      }else{
        $html .= $this->view->agenda[$indiceDias][0];
      }
      $html .= '</td>';
      $html .= '</tr>';
      if($ocorreuEvento){
        break;
      }
    } 
    $html .= '</tbody>';
    $html .= '</table>';

    return $html;
  }

}
