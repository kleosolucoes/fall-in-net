<?php

namespace Application\Controller;

use Application\Form\KleoForm;
use Application\Form\PonteForm;
use Application\Form\ProspectoForm;
use Application\Form\AtivoForm;
use Application\Form\AtivoCadastrarSenhaForm;
use Application\Form\AtivoAtualizacaoForm;
use Application\Model\ORM\RepositorioORM;
use Application\Model\Entity\Pessoa;
use Application\Model\Entity\GrupoPessoa;
use Application\Model\Entity\GrupoPessoaTipo;
use Application\Model\Entity\Tarefa;
use Application\Model\Entity\EventoFrequencia;
use Application\Model\Entity\PonteProspecto;
use Application\Model\Entity\FatoCiclo;
use Application\Model\Entity\TarefaTipo;
use Application\Model\Entity\Hierarquia;
use Application\Model\Entity\Grupo;
use Application\Model\Entity\PessoaHierarquia;
use Application\Model\Entity\GrupoResponsavel;
use Application\Model\Entity\GrupoPaiFilho;
use Doctrine\ORM\EntityManager;
use Exception;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use DateTime;

/**
 * Nome: AdmController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações da admintração
 */
class AdmController extends KleoController {

  private $grupo;
  /**
     * Contrutor sobrecarregado com os serviços de ORM
     */
  public function __construct(EntityManager $doctrineORMEntityManager = null) {

    if (!is_null($doctrineORMEntityManager)) {
      parent::__construct($doctrineORMEntityManager);
    }
  }

  public function indexAction() {
    $grupoEventos = self::getGrupo()->getGrupoEventoAcima();
    $token = $this->getEvent()->getRouteMatch()->getParam(self::stringToken, 0);    
    $arrayPeriodo = self::montaPeriodoPeloToken($grupoEventos, $token);
    $inicioDoCiclo = $arrayPeriodo[0];
    $fimDoCiclo = $arrayPeriodo[1];

    $grupoPessoas = self::getGrupo()->getGrupoPessoaAtivasNoPeriodo($inicioDoCiclo, $fimDoCiclo);
    $arrayTarefas = array();
    $arrayPontes = array();
    $arrayPontesParaCadastro = array();
    if($grupoPessoas){
      foreach($grupoPessoas as $grupoPessoa){
        if($grupoPessoa->getGrupoPessoaTipo()->getId() === GrupoPessoaTipo::PONTE){
          $arrayPontes[] = $grupoPessoa;
          $prospectosDaPonte = $grupoPessoa->getPessoa()->getPonteProspectoProspectos();
          if(count($prospectosDaPonte) < 4){
            $arrayPontesParaCadastro[] = $grupoPessoa; 
          }
        }
        foreach($grupoPessoa->getPessoa()->getTarefa() as $tarefa){
          $arrayTarefas[] = $tarefa;
        }
      }
    }


    $arrayAgenda = array();
    for($j = $inicioDoCiclo;$j <= $fimDoCiclo;$j++){
      $contadorDeTarrefas = 0;
      $diaParaComparar = date('Y-m-d', strtotime('now +'.$j.' days'));
      foreach($arrayTarefas as $tarefa){
        if($tarefa->getData_criacaoFormatoBandoDeDados() == $diaParaComparar){
          $arrayAgenda[$j][] = $tarefa;
          $contadorDeTarrefas++;
        }
      }
      foreach($grupoEventos as $grupoEvento){
        $diaDaSemana = date('N', strtotime('now +'.$j.' days')); 
        if($grupoEvento->getEvento()->getDia() == $diaDaSemana){
          $arrayAgenda[$j][] = $grupoEvento->getEvento();
          $contadorDeTarrefas++;
        }
      }
      if($contadorDeTarrefas === 0){
        $arrayAgenda[$j][0] = 'Sem tarefas nesse dia';  
      }
    }

    $formularioPonte = new PonteForm('Ponte');
    $formularioProspecto = null;
    if(count($arrayPontes)>0){
      $formularioProspecto = new ProspectoForm('Prospecto', $arrayPontesParaCadastro);
    }

    return new ViewModel(array(
      self::stringAgenda => $arrayAgenda,
      self::stringGrupoPessoas => $grupoPessoas,
      self::stringPontes => $arrayPontes,
      self::stringPontesParaCadastro => $arrayPontesParaCadastro,
      self::stringFormulario.'Ponte' => $formularioPonte,
      self::stringFormulario.'Prospecto' => $formularioProspecto,
      self::stringInicioDoCiclo => $inicioDoCiclo,
      self::stringFimDoCiclo => $fimDoCiclo,
      self::stringToken => $token,
    ));
  }

  public function montaPeriodoPeloToken($grupoEventos, $token){
    $diaDoEventoEmRelacaoHoje = 0;
    for($indiceDia = 0;$indiceDia <= 6;$indiceDia++){
      foreach($grupoEventos as $grupoEvento){
        $diaDaSemana = date('N', strtotime('now +'.$indiceDia.' days')); 
        if($grupoEvento->getEvento()->getDia() == $diaDaSemana){
          $diaDoEventoEmRelacaoHoje = $indiceDia;
        }
      }
    }
    $inicioDoCiclo = (6 - $diaDoEventoEmRelacaoHoje) * -1;
    $fimDoCiclo = $diaDoEventoEmRelacaoHoje;
    if($token == 1){
      $inicioDoCiclo += 7;
      $fimDoCiclo += 7;
    }
    if($token == -1){
      $inicioDoCiclo -= 7;
      $fimDoCiclo -= 7;
    }
    $arrayPeriodo[0] = $inicioDoCiclo;
    $arrayPeriodo[1] = $fimDoCiclo;

    return $arrayPeriodo;
  }

  /**
     * Função para validar e finalizar cadastro
     * GET /admPonteProspectoFinalizar
     */
  public function ponteProspectoFinalizarAction() {
    $request = $this->getRequest();
    if ($request->isPost()) {

      try {
        self::getRepositorio()->iniciarTransacao();

        $post = array_merge_recursive(
          $request->getPost()->toArray(), $request->getFiles()->toArray()
        );

        $pessoa = new Pessoa();
        if($post[KleoForm::inputGrupoPessoaTipo] == GrupoPessoaTipo::PONTE){
          $nomeFromulario = 'Ponte';
          $formulario = new PonteForm($nomeFromulario);

        }
        if($post[KleoForm::inputGrupoPessoaTipo] == GrupoPessoaTipo::PROSPECTO){
          $nomeFromulario = 'Prospecto';
          $grupoPessoas = self::getGrupo()->getGrupoPessoa();
          $arrayPontes = array();
          if($grupoPessoas){
            foreach($grupoPessoas as $grupoPessoa){
              if($grupoPessoa->getGrupoPessoaTipo()->getId() === GrupoPessoaTipo::PONTE){
                $arrayPontes[] = $grupoPessoa;
              }
            }
          }
          $formulario = new ProspectoForm($nomeFromulario, $arrayPontes);
        }
        $formulario->setInputFilter($pessoa->getInputFilterCadastrarPonteProspecto($nomeFromulario));
        $formulario->setData($post);

        /* validação */
        if ($formulario->isValid()) {
          $validatedData = $formulario->getData();
          $pessoa->exchangeArray($formulario->getData(), $nomeFromulario);
          self::getRepositorio()->getPessoaORM()->persistir($pessoa);
          $grupo = self::getGrupo();

          $grupoPessoaTipo = self::getRepositorio()->getGrupoPessoaTipoORM()->encontrarPorId($post[KleoForm::inputGrupoPessoaTipo]);
          $grupoPessoa = new GrupoPessoa();
          $grupoPessoa->setGrupo($grupo);
          $grupoPessoa->setPessoa($pessoa);
          $grupoPessoa->setGrupoPessoaTipo($grupoPessoaTipo);
          self::getRepositorio()->getGrupoPessoaORM()->persistir($grupoPessoa);

          if($post[KleoForm::inputGrupoPessoaTipo] == GrupoPessoaTipo::PROSPECTO){
            $ponteProspecto = new PonteProspecto();
            $ponte = self::getRepositorio()->getPessoaORM()->encontrarPorId($post[KleoForm::inputPonte]);
            $ponteProspecto->setPonteProspectoProspecto($pessoa);
            $ponteProspecto->setPonteProspectoPonte($ponte);
            self::getRepositorio()->getPonteProspectoORM()->persistir($ponteProspecto);
          }

          $grupoEventos = $grupo->getGrupoEventoAcima();
          $naoMudarDataDeCadastro = false;
          for($indice = 0;$indice <= 7;$indice++){
            foreach($grupoEventos as $grupoEvento){
              $diaDaSemana = date('N', strtotime('now +'.$indice.' days')); 
              if($grupoEvento->getEvento()->getDia() == $diaDaSemana && $indice !== 0){
                $this->cadastrarTarefa($pessoa, TarefaTipo::LIGAR, $indice);
                $this->cadastrarTarefa($pessoa, TarefaTipo::MENSAGEM, $indice);
                break;
              }
              if($indice === 0){
                $diaDaSemanaMais1 = date('N', strtotime('now +'.($indice+1).' days')); 
                if($grupoEvento->getEvento()->getDia() != $diaDaSemanaMais1){
                  $this->cadastrarTarefa($pessoa, TarefaTipo::LIGAR, $indice+1);
                }
                $diaDaSemanaMais2 = date('N', strtotime('now +'.($indice+2).' days')); 
                if($grupoEvento->getEvento()->getDia() != $diaDaSemanaMais2){
                  $this->cadastrarTarefa($pessoa, TarefaTipo::MENSAGEM, $indice+2);
                }
              }
            }
          }

          $numeroIdentificador = self::getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador(self::getRepositorio());
          if($post[KleoForm::inputGrupoPessoaTipo] == GrupoPessoaTipo::PONTE){
            self::getRepositorio()->getFatoCicloORM()->criarFatoCiclo($numeroIdentificador, FatoCiclo::PONTE, 1);          
          }
          if($post[KleoForm::inputGrupoPessoaTipo] == GrupoPessoaTipo::PROSPECTO){
            self::getRepositorio()->getFatoCicloORM()->criarFatoCiclo($numeroIdentificador, FatoCiclo::PROSPECTO, 1);                
          }

          self::getRepositorio()->fecharTransacao();

          return $this->redirect()->toRoute(self::rotaAdm, array(
            self::stringAction => self::stringIndex,
          ));

        } else {
          self::getRepositorio()->desfazerTransacao();
          echo $formulario->getMessages();
          return $this->forward()->dispatch(self::controllerAdm, array(
            self::stringAction => self::stringIndex,
            self::stringFormulario => $formulario,
          ));
        }
      } catch (Exception $exc) {
        self::getRepositorio()->desfazerTransacao();
        echo $exc->getMessage();
      }
    }
    return new ViewModel();
  }

  private function cadastrarTarefa($pessoa, $tipoTarefa, $diasAFrente) {
    $tarefaTipo = self::getRepositorio()->getTarefaTipoORM()->encontrarPorId($tipoTarefa);
    $naoMudarDataDeCadastro = false;
    $dataDeCriacao = date('Y-m-d', strtotime('now +'.$diasAFrente.' days'));
    $tarefa = new Tarefa();
    $tarefa->setPessoa($pessoa);
    $tarefa->setTarefaTipo($tarefaTipo);
    $tarefa->setDataEHoraDeCriacao($dataDeCriacao);
    self::getRepositorio()->getTarefaORM()->persistir($tarefa, $naoMudarDataDeCadastro);
  }

  /**
     * Muda a frequência de uma tarefa
     * @return Json
     */
  public function mudarFrequenciaTarefaAction() {
    $request = $this->getRequest();
    $response = $this->getResponse();
    if ($request->isPost()) {
      try {
        self::getRepositorio()->iniciarTransacao();
        $naoMudarDataDeCriacao = false;
        $post_data = $request->getPost();
        $valor = $post_data['valor'];
        $idTarefa = $post_data['idTarefa'];
        $tarefa = self::getRepositorio()->getTarefaORM()->encontrarPorId($idTarefa);
        $tarefa->setRealizada($valor);
        $tarefa->setDataEHoraDeAlteracao();
        self::getRepositorio()->getTarefaORM()->persistir($tarefa, $naoMudarDataDeCriacao);

        if($valor == 'S'){
          $valor = 1;
        }else{
          $valor = -1;
        }

        $numeroIdentificador = self::getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador(self::getRepositorio());
        if($tarefa->getTarefaTipo()->getId() == TarefaTipo::LIGAR){
          self::getRepositorio()->getFatoCicloORM()->criarFatoCiclo($numeroIdentificador, FatoCiclo::LIGACAO, $valor);          
        }
        if($tarefa->getTarefaTipo()->getId() == TarefaTipo::MENSAGEM){
          self::getRepositorio()->getFatoCicloORM()->criarFatoCiclo($numeroIdentificador, FatoCiclo::MENSAGEM, $valor);          
        }

        self::getRepositorio()->fecharTransacao();
        $response->setContent(Json::encode(
          array('response' => 'true')
        ));
      } catch (Exception $exc) {
        $self::getRepositorio()->desfazerTransacao();
        echo $exc->getTraceAsString();
      }
    }
    return $response;
  }


  /**
     * Muda a frequência de uma frequencia
     * @return Json
     */
  public function mudarFrequenciaEventoAction() {
    $request = $this->getRequest();
    $response = $this->getResponse();
    if ($request->isPost()) {
      try {
        self::getRepositorio()->iniciarTransacao();
        $post_data = $request->getPost();
        $valor = $post_data['valor'];
        $idEvento = $post_data['idEvento'];
        $idPessoa = $post_data['idPessoa'];
        $diaRealDoEvento = $post_data['diaRealDoEvento'];
        $diaFormatado = DateTime::createFromFormat('Y-m-d', $diaRealDoEvento);
        $evento = self::getRepositorio()->getEventoORM()->encontrarPorId($idEvento);
        $pessoa = self::getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);

        $eventosFiltrado = $pessoa->getEventoFrequenciaFiltradoPorEventoEDia($idEvento, $diaRealDoEvento);
        if ($eventosFiltrado) {
          /* Frequencia existe */
          $frequencia = $eventosFiltrado;
          $frequencia->setFrequencia($valor);
          self::getRepositorio()->getEventoFrequenciaORM()->persistir($frequencia);
        } else {
          $eventoFrequencia = new EventoFrequencia();
          $eventoFrequencia->setEvento($evento);
          $eventoFrequencia->setPessoa($pessoa);
          $eventoFrequencia->setFrequencia($valor);
          $eventoFrequencia->setDia($diaFormatado);
          self::getRepositorio()->getEventoFrequenciaORM()->persistir($eventoFrequencia);
        }

        $numeroIdentificador = self::getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador(self::getRepositorio());
        if($valor == 'S'){
          $valor = 1;
        }else{
          $valor = -1;
        }
        self::getRepositorio()->getFatoCicloORM()->criarFatoCiclo($numeroIdentificador, FatoCiclo::FREQUENCIA, $valor);          

        self::getRepositorio()->fecharTransacao();
        $response->setContent(Json::encode(
          array('response' => 'true')
        ));
      } catch (Exception $exc) {
        self::getRepositorio()->desfazerTransacao();
        echo $exc->getTraceAsString();
      }
    }
    return $response;
  }

  /**
     * clicar em uma acao
     * @return Json
     */
  public function clicarAction() {
    $request = $this->getRequest();
    $response = $this->getResponse();
    if ($request->isPost()) {
      try {
        self::getRepositorio()->iniciarTransacao();       
        $post_data = $request->getPost();      
        $tipoClique = $post_data['tipoClique'];

        $numeroIdentificador = self::getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador(self::getRepositorio());
        if($tipoClique == FatoCiclo::CLIQUE_LIGACAO){
          self::getRepositorio()->getFatoCicloORM()->criarFatoCiclo($numeroIdentificador, FatoCiclo::CLIQUE_LIGACAO, 1);          
        }
        if($tipoClique == FatoCiclo::CLIQUE_MENSAGEM){
          self::getRepositorio()->getFatoCicloORM()->criarFatoCiclo($numeroIdentificador, FatoCiclo::CLIQUE_MENSAGEM, 1);          
        }

        self::getRepositorio()->fecharTransacao();
        $response->setContent(Json::encode(
          array('response' => 'true')
        ));
      } catch (Exception $exc) {
        $self::getRepositorio()->desfazerTransacao();
        echo $exc->getTraceAsString();
      }
    }
    return $response;
  }

  public function relatorioAction(){
    $grupoEventos = self::getGrupo()->getGrupoEventoAcima();
    $token = $this->getEvent()->getRouteMatch()->getParam(self::stringToken, 0);    
    $arrayPeriodo = self::montaPeriodoPeloToken($grupoEventos, $token);
    $inicioDoCiclo = $arrayPeriodo[0];
    $fimDoCiclo = $arrayPeriodo[1];

    $numeroIdentificador = self::getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador(self::getRepositorio());
    $tipoComparacao = 1; 
    $dataIncial = date('Y-m-n', strtotime('now +'.$inicioDoCiclo.' days'));  
    $dataFinal = date('Y-m-n', strtotime('now +'.$fimDoCiclo.' days'));
    $relatorio = self::montaRelatorio(self::getRepositorio(), $numeroIdentificador, $dataIncial, $dataFinal, $tipoComparacao);          

    return new ViewModel(array(
      self::stringRelatorio => $relatorio,
      self::stringToken => $token,
    ));
  }

  public static function montaRelatorio($repositorioORM, $numeroIdentificador, $dataIncial, $dataFinal, $tipoComparacao) {
    unset($relatorio);
    $relatorio = $repositorioORM->getFatoCicloORM()->montarRelatorioPorNumeroIdentificador($numeroIdentificador, $dataIncial, $dataFinal, $tipoComparacao);
    if(!$relatorio[0][self::relatorioPonte]){
      $relatorio[0][self::relatorioPonte] = 0;
    }
    if(!$relatorio[0][self::relatorioProspecto]){
      $relatorio[0][self::relatorioProspecto] = 0;
    }
    if(!$relatorio[0][self::relatorioLigacao]){
      $relatorio[0][self::relatorioLigacao] = 0;
    }
    if(!$relatorio[0][self::relatorioMensagem]){
      $relatorio[0][self::relatorioMensagem] = 0;
    }

    $relatorio[0][self::relatorioPontePerformance] = $relatorio[0][self::relatorioPonte] / self::metaPonte * 100;
    $relatorio[0][self::relatorioProspectoPerformance] = $relatorio[0][self::relatorioProspecto] / self::metaProspecto * 100;
    $relatorio[0][self::relatorioLigacaoPerformance] = $relatorio[0][self::relatorioLigacao] / self::metaProspecto * 100;
    $relatorio[0][self::relatorioMensagemPerformance] = $relatorio[0][self::relatorioMensagem] / self::metaProspecto * 100;

    return $relatorio;
  }

  /**
     * Função que direciona a tela de acesso
     * GET /admSair
     */
  public function sairAction() {
    /* Fechando a sessão */
    $sessao = $this->getSessao();
    $sessao->getManager()->destroy();

    /* Redirecionamento */
    return $this->redirect()->toRoute(self::rotaPub, array(
      self::stringAction => self::stringLogin,
    ));
  }


  public function ativoAction(){
    $formulario = new AtivoForm('grupo');

    return new ViewModel(array(
      self::stringFormulario => $formulario,
    ));
  }


  /**
     * Tela com confrmação de cadastro de grupo
     * POST /cadastroAtivoFinalizar
     */
  public function ativoFinalizarAction() {
    //CircuitoController::verificandoSessao(new Container(Constantes::$NOME_APLICACAO), $this);
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        self::getRepositorio()->iniciarTransacao();

        $post = array_merge_recursive(
          $request->getPost()->toArray(), $request->getFiles()->toArray()
        );

        $pessoa = new Pessoa();
        $formulario = new AtivoForm();
        $formulario->setInputFilter($pessoa->getInputFilterCadastrarAtivo());
        $formulario->setData($post);

        /* validação */
        if ($formulario->isValid()) {
          $validatedData = $formulario->getData();
          $pessoa->exchangeArray($formulario->getData());

          /* Criar Grupo */
          $grupoNovo = new Grupo();
          $this->getRepositorio()->getGrupoORM()->persistir($grupoNovo);
          /* Pessoa */
          $tokenDeAgora = $pessoa->gerarToken();
          $pessoa->setToken($tokenDeAgora);
          $pessoa->setAtualizar_dados('S');
          $this->getRepositorio()->getPessoaORM()->persistir($pessoa);
          /* Criar hierarquia */
          $hierarquia = $this->getRepositorio()->getHierarquiaORM()->encontrarPorId(Hierarquia::ATIVO_SEM_REUNIAO);
          $pessoaHierarquia = new PessoaHierarquia();
          $pessoaHierarquia->setPessoa($pessoa);
          $pessoaHierarquia->setHierarquia($hierarquia);
          $this->getRepositorio()->getPessoaHierarquiaORM()->persistir($pessoaHierarquia);
          /* Criar Grupo_Responsavel */
          $grupoResponsavelNovo = new GrupoResponsavel();
          $grupoResponsavelNovo->setPessoa($pessoa);
          $grupoResponsavelNovo->setGrupo($grupoNovo);
          $this->getRepositorio()->getGrupoResponsavelORM()->persistir($grupoResponsavelNovo);
          /* Criar Grupo_Pai_Filho */
          $grupoAtualSelecionado = self::getGrupo();
          $grupoPaiFilhoNovo = new GrupoPaiFilho();
          $grupoPaiFilhoNovo->setGrupoPaiFilhoPai($grupoAtualSelecionado);
          $grupoPaiFilhoNovo->setGrupoPaiFilhoFilho($grupoNovo);
          $this->getRepositorio()->getGrupoPaiFilhoORM()->persistir($grupoPaiFilhoNovo);

          $this->getRepositorio()->fecharTransacao();

          $email = $pessoa->getEmail();
          $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($email);
          /* Enviar Email */
          $sessao = self::getSessao();
          self::enviarEmailParaCompletarOsDados($this->getRepositorio(), $sessao->idPessoa, $tokenDeAgora, $pessoa);

          return $this->redirect()->toRoute(self::rotaAdm, array(
            self::stringAction => self::stringAtivoFinalizado,
          ));
        }else{
          self::getRepositorio()->desfazerTransacao();
          self::mostrarMensagensDeErroFormulario($formulario->getMessages());
          return $this->forward()->dispatch(self::controllerAdm, array(
            self::stringAction => self::stringAtivo,
            self::stringFormulario => $formulario,
          ));
        }
      } catch (Exception $exc) {
        $this->getRepositorio()->desfazerTransacao();
        echo $exc->getTraceAsString();
        $this->direcionaErroDeCadastro($exc->getMessage());
        self::direcionandoAoLogin($this);
      }

    }
  }

  public function ativoFinalizadoAction() {
    return new ViewModel();
  } 

  public static function enviarEmailParaCompletarOsDados($repositorio, $idPessoaLogada, $tokenDeAgora, $pessoa) {
    $pessoaLogada = $repositorio->getPessoaORM()->encontrarPorId($idPessoaLogada);

    $subject = 'A Fabrica';
    $toEmail[] = $pessoa->getEmail();
    $content = '<p>Bem vindo a Fábrica</p>';
    $content .= '<p>Para finalizar seu cadastro clique no link abaixo.</p>';
    $content .= '<p><a href="www.afabricaoficial.com.br/ativoCadastrarSenha/'.$tokenDeAgora.'">Clique Aqui!</a></p>';

    self::enviarEmail($toEmail, $subject, $content);
  }

  /**
     * Muda a frequência de uma frequencia
     * @return Json
     */
  public function verificarEmailCadastradoAction() {
    $request = $this->getRequest();
    $response = $this->getResponse();
    if ($request->isPost()) { 
      try {
        $resposta = false;
        $post_data = $request->getPost();
        $email = $post_data['email'];

        $resultado = self::getRepositorio()->getPessoaORM()->encontrarPorEmail($email);
        if($resultado){
          $resposta = true;
        }
        $response->setContent(Json::encode(
          array('response' => $resposta)
        ));
      } catch (Exception $exc) {
        echo $exc->getMenssage();
      }
    }
    return $response;
  }

  /**
     * Tela com atualização de cadastro de ativo
     * GET /admAtivoAtualizacao
     */
  public function ativoAtualizacaoAction() {
    $sessao = $this->getSessao();
    $formulario = new AtivoAtualizacaoForm('Ativo', $sessao->idPessoa);
    return new ViewModel(array(self::stringFormulario => $formulario));
  }

  public function enviarSMSAction() {
    $resposta = false;
    $request = $this->getRequest();
    $response = $this->getResponse();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $telefone = $post_data[KleoForm::inputTelefone];

        $resposta = self::enviarSMS($telefone);

        $dados = array();
        $dados[self::stringResposta] = $resposta;
        $response->setContent(Json::encode($dados));
      } catch (Exception $exc) {
        echo $exc->getTraceAsString();
      }
    }
    return $response;
  }

  public static function enviarSMS($numero, $mensagem = 'Codigo de ativacao da U.R.S.A. - ') {
    $validacao[1] = '1658';
    $validacao[2] = '2487';
    $validacao[3] = '3694';
    $validacao[4] = '4851';

    $numeroDe1A4 = rand(1, 4);
    $mensagem = $mensagem . $validacao[$numeroDe1A4];

    $msgEncoded = urlencode($mensagem);
    $urlChamada = "https://www.facilitamovel.com.br/api/simpleSend.ft?user=diegokort&password=qwaszx159753&destinatario=" . $numero . "&msg=" . $msgEncoded;
    file_get_contents($urlChamada);
    return true;
  }

  /**
     * Atualização dos dados depois de cadastrar o grupo
     * POST /admAtivoAtualizar
     */
  public function ativoAtualizarAction() {
    //CircuitoController::verificandoSessao(new Container(Constantes::$NOME_APLICACAO), $this);
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();

        $pessoa = self::getRepositorio()->getPessoaORM()->encontrarPorId($post_data[KleoForm::inputId]);
        $pessoa->setTelefone($post_data[KleoForm::inputTelefone]);
        $pessoa->setAtualizar_dados('N');
        $naoMudarDataDeCriacao = false;
        self::getRepositorio()->getPessoaORM()->persistir($pessoa, $naoMudarDataDeCriacao);
      } catch (Exception $exc) {
        $this->direcionaErroDeCadastro($exc->getMessage());
        return $this->redirect()->toRoute(self::rotaPub, array(
          self::stringAction => self::stringIndex,
        ));
      }
      return $this->redirect()->toRoute(self::rotaAdm, array(
        self::stringAction => self::stringIndex,
      ));
    }
  }

  public function getGrupo() {
    if (!$this->grupo) {
      $sessao = self::getSessao();
      $pessoaLogada = self::getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
      $this->grupo = $pessoaLogada->getResponsabilidadesAtivas()[0]->getGrupo();
    }
    return $this->grupo;
  }

}
