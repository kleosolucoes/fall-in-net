<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Form\NovoEmailForm;
use Application\Model\Entity\EntidadeTipo;
use Application\Model\Entity\EventoTipo;
use Application\Model\Entity\Hierarquia;
use Exception;
use Zend\Json\Json;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Nome: PrincipalController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações da tela principal
 */
class PrincipalController extends CircuitoController {

    /**
     * Função padrão, traz a tela principal
     * GET /principal
     */
    public function indexAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);

        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $eCasal = $grupo->verificaSeECasal();
        $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupo);

        $idPessoa = $sessao->idPessoa;
        $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);

        $tipoRelatorioPessoal = 1;
        $tipoRelatorioEquipe = 2;
        $periodo = -1;
        $quantidadeDeCiclosPassados = 8;
        $relatorio = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodo, $tipoRelatorioPessoal);
        $relatorioEquipe = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodo, $tipoRelatorioEquipe);

        /* encontrando os periodos do mes atual e anterior */
        $arrayPeriodos = Funcoes::encontrarNumeroDePeriodosNoMesAtualEAnterior();
        $relatorioMedio = array();
        $relatorioMedio['pessoalAtual'] = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $arrayPeriodos['periodoMesAtualInicial'], $tipoRelatorioPessoal, $arrayPeriodos['periodoMesAtualFinal']);
        $relatorioMedio['pessoalAnterior'] = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $arrayPeriodos['periodoMesAnteriorInicial'], $tipoRelatorioPessoal, $arrayPeriodos['periodoMesAnteriorlFinal']);
        $relatorioMedio['celulasAtual'] = RelatorioController::saberQuaisdasMinhasCelulasSaoDeElite($this->getRepositorio(), $grupo, $arrayPeriodos['periodoMesAtualInicial'], $arrayPeriodos['periodoMesAtualFinal']);
        $relatorioMedio['celulasAnterior'] = RelatorioController::saberQuaisdasMinhasCelulasSaoDeElite($this->getRepositorio(), $grupo, $arrayPeriodos['periodoMesAnteriorInicial'], $arrayPeriodos['periodoMesAnteriorlFinal']);

        $hierarquias = $this->getRepositorio()->getHierarquiaORM()->encontrarTodas();
        $rankingsMembresia = $this->getRepositorio()->getFatoRankingORM()->buscarTodosRegistrosEntidade('ranking_membresia', 'asc');
        $rankingsCelula = $this->getRepositorio()->getFatoRankingORM()->buscarTodosRegistrosEntidade('ranking_celula', 'asc');

        $dados = array();
        $dados['pessoa'] = $pessoa;
        $dados['eCasal'] = $eCasal;
        $dados['quantidadeDeCiclosPassados'] = $quantidadeDeCiclosPassados;
        $arrayPeriodo = Funcoes::montaPeriodo($periodo);
        $dados['periodoExtenso'] = $arrayPeriodo[0];
        $dados['relatorio'] = $relatorio;
        $dados['relatorioEquipe'] = $relatorioEquipe;
        $dados['relatorioMedio'] = $relatorioMedio;
        $dados['repositorio'] = $this->getRepositorio();
        $dados['hierarquias'] = $hierarquias;
        $dados['grupo'] = $grupo;
        $dados['rankingsMembresia'] = $rankingsMembresia;
        $dados['rankingsCelula'] = $rankingsCelula;

        $grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($periodo);
        if ($grupoPaiFilhoFilhos) {
            $relatorioDiscipulos = array();
            $relatorioDiscipulosPessoal = array();
            $discipulos = array();
            foreach ($grupoPaiFilhoFilhos as $gpFilho) {
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupoFilho);
                $relatorio12 = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodo, $tipoRelatorioEquipe);
                $relatorio12Pessoal = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodo, $tipoRelatorioPessoal);
                if ($relatorio12['celulaQuantidade'] > 0) {
                    if ($relatorio12['celulaRealizadas'] < $relatorio12['celulaQuantidade']) {
                        $relatorioDiscipulos[$grupoFilho->getId()] = $relatorio12;
                        $relatorioDiscipulosPessoal[$grupoFilho->getId()] = $relatorio12Pessoal;
                        $discipulos[] = $gpFilho;
                    }
                }

                $grupoPaiFilhoFilhos144 = $grupoFilho->getGrupoPaiFilhoFilhosAtivos($periodo);
                if ($grupoPaiFilhoFilhos144) {
                    foreach ($grupoPaiFilhoFilhos144 as $gpFilho144) {
                        $grupoFilho144 = $gpFilho144->getGrupoPaiFilhoFilho();
                        $numeroIdentificador144 = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupoFilho144);
                        $relatorio144 = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador144, $periodo, $tipoRelatorioEquipe);
                        if ($relatorio144['celulaQuantidade'] > 0) {
                            if ($relatorio144['celulaRealizadas'] < $relatorio144['celulaQuantidade']) {
                                $relatorioDiscipulos[$grupoFilho144->getId()] = $relatorio144;
                            }
                        }
                    }
                }
            }
            $dados['discipulos'] = $discipulos;
            $dados['discipulosRelatorio'] = $relatorioDiscipulos;
            $dados['discipulosRelatorioPessoal'] = $relatorioDiscipulosPessoal;
        }

        $view = new ViewModel($dados);
        /* Javascript */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate('layout/layout-js-principal');
        $view->addChild($layoutJS, 'layoutJSPrincipal');

        if ($sessao->jaMostreiANotificacao) {
            unset($sessao->mostrarNotificacao);
            unset($sessao->nomePessoa);
            unset($sessao->exclusao);
            unset($sessao->jaMostreiANotificacao);
        }

        return $view;
    }

    public function verAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idSessao = $sessao->idSessao;
//        unset($sessao->idSessao);
        if ($idSessao) {

            $grupoSessao = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idSessao);
            $tenhoDiscipulosAtivos = false;
            $quantidadeDeDiscipulos = count($grupoSessao->getGrupoPaiFilhoFilhosAtivos());
            if ($quantidadeDeDiscipulos > 0) {
                $tenhoDiscipulosAtivos = true;
            }

            $mostrarParaReenviarEmails = false;
            foreach ($grupoSessao->getResponsabilidadesAtivas() as $grupoResponsavel) {
                $pessoaSelecionada = $grupoResponsavel->getPessoa();
                if ($pessoaSelecionada->getToken()) {
                    $mostrarParaReenviarEmails = true;
                }
            }

            $entidade = $grupoSessao->getEntidadeAtiva();
            $entidadeLogada = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
            $listagemDeEventos = $entidade->getGrupo()->getGrupoEventoAtivosPorTipo(EventoTipo::tipoCelula);

            $dados = array();
            $dados['idGrupo'] = $idSessao;
            $dados['entidade'] = $entidade;
            $dados['idEntidadeTipo'] = $entidadeLogada->getTipo_id();
            $dados['tenhoDiscipulosAtivos'] = $tenhoDiscipulosAtivos;
            $dados['mostrarParaReenviarEmails'] = $mostrarParaReenviarEmails;
            $dados['responsabilidades'] = $grupoSessao->getResponsabilidadesAtivas();
            $dados[Constantes::$LISTAGEM_EVENTOS] = $listagemDeEventos;
            $dados[Constantes::$TIPO_EVENTO] = EventoTipo::tipoCelula;
            $dados['mostrarExcluirCelula'] = false;
            if ($entidadeLogada->getEntidadeTipo()->getId() === EntidadeTipo::equipe ||
                    $entidadeLogada->getEntidadeTipo()->getId() === EntidadeTipo::igreja) {
                $dados['mostrarExcluirCelula'] = true;
            }

            return new ViewModel($dados);
        } else {
            return $this->redirect()->toRoute('principal');
        }
    }

    public function grupoExclusaoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        try {
            $this->getRepositorio()->iniciarTransacao();
            $idSessao = $sessao->idSessao;
            unset($sessao->idSessao);
            if ($idSessao) {

                $grupoSessao = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idSessao);

                $dados = array();
                $dados['idGrupo'] = $idSessao;
                $dados['entidade'] = $grupoSessao->getEntidadeAtiva();
                $dados[Constantes::$EXTRA] = null;

                $view = new ViewModel($dados);
                /* Javascript */
                $layoutJS = new ViewModel();
                $layoutJS->setTemplate('layout/layout-js-exclusao');
                $view->addChild($layoutJS, 'layoutJSExclusao');

                return $view;
            } else {
                return $this->redirect()->toRoute('principal');
            }
            $this->getRepositorio()->fecharTransacao();
        } catch (Exception $exc) {
            $this->getRepositorio()->desfazerTransacao();
            echo $exc->getTraceAsString();
            $this->direcionaErroDeCadastro($exc->getMessage());
            CircuitoController::direcionandoAoLogin($this);
        }
    }

    public function grupoExclusaoConfirmacaoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idSessao = $sessao->idSessao;
        if ($idSessao) {
            $this->getRepositorio()->iniciarTransacao();
            try {
                $grupoSessao = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idSessao);

                $grupoPaiFilhoPai = $grupoSessao->getGrupoPaiFilhoPaiAtivo();
                $grupoPaiFilhoPai->setDataEHoraDeInativacao();
                $this->getRepositorio()->getGrupoPaiFilhoORM()->persistir($grupoPaiFilhoPai, false);

                foreach ($grupoSessao->getResponsabilidadesAtivas() as $grupoResponsavel) {
                    $grupoResponsavel->setDataEHoraDeInativacao();
                    $this->getRepositorio()->getGrupoResponsavelORM()->persistir($grupoResponsavel, false);
                }
                $this->inativarFatoLiderPorGrupo($grupoSessao);

                $this->getRepositorio()->fecharTransacao();
                unset($sessao->idSessao);
                $sessao->mostrarNotificacao = true;
                $sessao->nomePessoa = $grupoSessao->getEntidadeAtiva()->infoEntidade();
                $sessao->exclusao = true;
                return $this->redirect()->toRoute('principal');
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
                $this->getRepositorio()->desfazerTransacao();
            }
        }
    }

    public function novoEmailParaEnviarAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idSessao = $sessao->idSessao;
        if ($idSessao) {
            $form = new NovoEmailForm(Constantes::$FORM, $idSessao);

            $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idSessao);

            $view = new ViewModel(
                    array(
                Constantes::$FORM => $form,
                'nome' => $pessoa->getNome(),
            ));
            $layoutJS = new ViewModel();
            $layoutJS->setTemplate('layout/layout-js-enviar-email');
            $view->addChild($layoutJS, 'layoutJSEnviarEmail');
            unset($sessao->idSessao);
            return $view;
        } else {
            return $this->redirect()->toRoute('principal');
        }
    }

    public function enviarEmailAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $this->getRepositorio()->iniciarTransacao();
                $post_data = $request->getPost();
                $idPessoa = $post_data[Constantes::$INPUT_ID_PESSOA];
                $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
                $pessoa->setEmail($post_data[Constantes::$INPUT_EMAIL]);
                $setarDataEHora = false;
                $this->getRepositorio()->getPessoaORM()->persistir($pessoa, $setarDataEHora);
                if ($pessoa->getToken()) {
                    CadastroController::enviarEmailParaCompletarOsDados($this->getRepositorio(), $sessao->idPessoa, $pessoa->getToken(), $pessoa);
                }
                $sessao->mostrarNotificacao = true;
                $sessao->emailEnviado = true;
                $this->getRepositorio()->fecharTransacao();
                return $this->redirect()->toRoute('principal');
            } catch (Exception $exc) {
                $this->getRepositorio()->desfazerTransacao();
                echo $exc->getMessage();
            }
        }
    }

    public function chamadaAction() {
        return new ViewModel();
    }

    /**
     * Controle de funçoes da tela de cadastro
     * @return Json
     */
    public function funcoesAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $funcao = $post_data[Constantes::$FUNCAO];
                $id = $post_data[Constantes::$ID];
                $sessao->idSessao = $id;
                $response->setContent(Json::encode(
                                array(
                                    'response' => 'true',
                                    'tipoDeRetorno' => 1,
                                    'url' => '/' . $funcao,
                )));
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }
        }
        return $response;
    }

}
