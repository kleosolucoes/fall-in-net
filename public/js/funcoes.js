function kleo(action, id) {
  mostrarSplash();
  $.post(
    '/admKleo',
    {
      action: action,
      id: id
    },
    function (data) {
      if (data.response) {
        location.href = data.url;
      }
    }, 'json');
}

function validarExclusao(action, id) {
  var resposta = confirm('Confirma Exclusão?');
  if (resposta) {
    kleo(action, id);
  } else {
    return false;
  }
}

function mudarPaginaComLoader(url) {
  mostrarSplash();
  location.href = url;
}

function submeterFormulario(form) {
  var temErros = false;
  var inputs;
  if(form){
    inputs = form.elements;
  }else{
    inputs = document.getElementsByTagName('input');
  }
  var i;
  for (i = 0; i < inputs.length; i++) {
    if(inputs[i].type == 'text' ||
       inputs[i].type == 'number'){
      if(validacoesFormulario(inputs[i])){
        temErros = true;
      } 
    }
  }
  if(!temErros){
    mostrarSplash();
    form.submit();
  }
}

function validarSePassoTemErros(campos){
  var temErros = false;
  for (i = 0; i < campos.length; i++) {
    var elemento = document.getElementById(campos[i]);
    if(validacoesFormulario(elemento)){
      temErros = true;
    } 
  }
  return temErros;
}

function isEmail(email) {
  er = /^[a-zA-Z0-9][a-zA-Z0-9\._-]+@([a-zA-Z0-9\._-]+\.)[a-zA-Z-0-9]{2,3}$/;
  if (!er.exec(email)) {
    return false;
  } else {
    return true;
  }
}
function isCPF(strCPF) {
  var Soma;
  var Resto;
  Soma = 0;
  if (strCPF == "00000000000") return false;

  for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
  Resto = (Soma * 10) % 11;

  if ((Resto == 10) || (Resto == 11))  Resto = 0;
  if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;

  Soma = 0;
  for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
  Resto = (Soma * 10) % 11;

  if ((Resto == 10) || (Resto == 11))  Resto = 0;
  if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
  return true;
}

$(window).bind("load", function () {
  // Remove splash screen after load
  escondeSplash();

  if($('#ancora').offset()){
    targetOffset = $('#ancora').offset().top;

    $('html, body').animate({ 
      scrollTop: targetOffset - 100
    }, 1000); 
  }

  if($('#panelWizard').offset()){
    var opcoes = Plugin.getDefaults("wizard");
    opcoes.step = '.wizard-pane'; 
    opcoes.buttonsAppendTo = '.panel-body';
    opcoes.templates = {
      buttons: function() {
        const options = this.options;
        return `<div class="wizard-buttons"><a class="btn btn-default btn-outline wizard-back float-left" href="#${this.id}" data-wizard="back" role="button">${options.buttonLabels.back}</a><a id="botaoProximo" class="btn btn-primary btn-outline wizard-next float-right" href="#${this.id}" data-wizard="next" role="button">${options.buttonLabels.next}</a><a id="botaoConcluir" onclick="submeterFormulario(document.getElementById(\'form\'));" class="btn btn-success hidden-xs-up btn-outline wizard-finish float-right" href="#${this.id}" data-wizard="finish" role="button">${options.buttonLabels.finish}</a></div>`;
      }
    };
    opcoes.buttonLabels = {
      next: 'Próximo',
      back: 'Voltar',
      finish: 'Concluir'
    };
    opcoes.onNext = function onNext(prev, step) {
      mostrarSplash();
      switch(step.index){
        case 1:
          var camposDadosPessoais = ['inputNome', 'inputDocumento', 'inputDia', 'inputMes', 'inputAno', 'inputSexo'];
          var passoDadosPessoaisValido = validarSePassoTemErros(camposDadosPessoais);
          if(passoDadosPessoaisValido){
            $('#panelWizard').wizard('goTo', 0);
          }else{
            $('#passo2').addClass('current');
            $('#botaoProximo').addClass('hidden-xs-up');
            $('#botaoConcluir').removeClass('hidden-xs-up');
          }
          break;
        case 2:
          var camposEmail = ['inputEmail', 'inputRepetirEmail'];
          var passoEmailValido = validarSePassoTemErros(camposEmail);
          if(passoEmailValido){
            $('#panelWizard').wizard('goTo', 1);
          }
          break;
      }
      escondeSplash();
    }
    opcoes.onBack = function onBack(prev, step) {
      $('#botaoProximo').removeClass('hidden-xs-up');
      $('#botaoConcluir').addClass('hidden-xs-up');
    }
    $('#panelWizard').wizard(opcoes); 
  }

});

var tipoTarefa = 1;
var tipoFrequencia = 2;
function mudarFrequencia(tipo, idTarefa, idEventoOuTipoTarefa, idPessoa, diaRealDoEvento, idPonte) {
  var faThumbsDown = 'fa-thumbs-down';
  var faThumbsUp = 'fa-thumbs-up';
  var disabled = 'disabled';
  var iconefaThumbsDown = '<i class="icon ' + faThumbsDown + '"></i>';
  var iconefaThumbsUp = '<i class="icon ' + faThumbsUp + '"></i>';
  var loader = '<img width="11" hegth="11" src="/img/loader.gif"></i>';
  var btnDefault = 'btn-default';
  var btnSuccess = 'btn-primary';
  var btnTransicao = 'btn-default';
  var botao;
  if(tipo === tipoTarefa){
    botao = $('#botao_' + idTarefa);
  }
  if(tipo === tipoFrequencia){
    botao = $('#botao_' + idEventoOuTipoTarefa + '_' + idPessoa);
  }

  var valor = 'N';
  if (botao.hasClass(btnDefault)) {
    valor = "S";
  }
  botao.html(loader);
  botao.removeClass(btnDefault);
  botao.removeClass(btnSuccess);
  botao.addClass(btnTransicao);

  /* Desabilitar botão ate terminar o processamento */
  botao.addClass(disabled);
  var dados, url;
  if(tipo === tipoTarefa){
    dados = { 
      valor: valor,
      idTarefa: idTarefa,
    };
    url = 'Tarefa';
  }
  if(tipo === tipoFrequencia){
    dados = { 
      valor: valor,
      idEvento: idEventoOuTipoTarefa,
      idPessoa: idPessoa,
      diaRealDoEvento: diaRealDoEvento,
    };
    url = 'Evento';
  }

  $.post(
    "/admMudarFrequencia"+url,
    dados,
    function (data) {
      if (data.response) {
        botao.removeClass(btnTransicao);
        botao.html('');
        if (valor == "S") {
          botao.addClass(btnSuccess);
          botao.html(iconefaThumbsUp);
        } else {
          botao.addClass(btnDefault);
          botao.html(iconefaThumbsDown);
        }
        botao.removeClass(disabled);

        if(idPonte !== 0){
          var valorAdicionarABarraDeProgresso = 0;
          if (valor == "S") {
            valorAdicionarABarraDeProgresso = '6.25';
          } else {
            valorAdicionarABarraDeProgresso = -'6.25';
          }
          var elementoContador;
          // tarefa ligar
          if(idEventoOuTipoTarefa == 1){
            elementoContador = $('#contador_ligacao_'+idPessoa);
          }
          // tarefa mensagem
          if(idEventoOuTipoTarefa == 2){
            elementoContador = $('#contador_mensagem_'+idPessoa);
          }
          if(parseInt(elementoContador.val()) === 1 && valor == "S"){
            elementoContador.val(2);
          }
          if(parseInt(elementoContador.val()) === 1 && valor == "N"){
            atualizarBarraDeProgresso(idPonte, valorAdicionarABarraDeProgresso);  
            elementoContador.val(0);
          }
          if(parseInt(elementoContador.val()) === 2 && valor == "N"){
            elementoContador.val(1);
          }
          if(parseInt(elementoContador.val()) === 0 && valor == "S"){
            atualizarBarraDeProgresso(idPonte, valorAdicionarABarraDeProgresso);  
            elementoContador.val(1);
          }

        }
      }
    }, 'json');
}

var tipoPonte = 1;
var tipoProspecto = 2;
var hidden = 'hidden';
function selecionarPonteProspecto(tipo){
  var divFormularioPonte = $('#divFormularioPonte');
  var divFormularioProspecto = $('#divFormularioProspecto'); 
  var modalTitle = $('#modalTitle');
  if(tipo === tipoPonte){
    divFormularioPonte.css("display", "block");
    divFormularioProspecto.css("display", "none");
    modalTitle.html('Nova Ponte');
  }
  if(tipo === tipoProspecto){
    divFormularioPonte.css("display", "none");
    divFormularioProspecto.css("display", "block");
    modalTitle.html('Novo Prospecto');
  }
}

var tipoLigacao = 6;
var tipoMensagem = 7;
function clicarAcao(tipo, telefone, nome){
  dados = { 
    tipoClique: tipo,
  };
  $.post(
    "/admClicar",
    dados,
    function (data) {
      if(tipo === tipoLigacao){
        location.href = 'tel:+55' + telefone;
      }
      if(tipo === tipoMensagem){
        var url = 'https://api.whatsapp.com/send?phone=55' + telefone + '&text=Bom%20dia%20' + nome;
        location.href = url;
      }
    }, 'json');
}

function pegaValorBarraDeProgresso(id) {
  return $('#divBarraDeProgresso_'+id).attr("aria-valuenow");
}

function atualizarBarraDeProgresso(id, valorParaSomar) {
  valorParaSomar = parseFloat(valorParaSomar);
  var valorAtualDaBarraDeProgresso = pegaValorBarraDeProgresso(id);
  var valorAtualizadoDaBarraDeProgresso = parseFloat(valorAtualDaBarraDeProgresso) + valorParaSomar;
  var stringPercentual = '%';
  $('#divBarraDeProgresso_'+id)
    .attr("aria-valuenow", valorAtualizadoDaBarraDeProgresso)
    .html(valorAtualizadoDaBarraDeProgresso + stringPercentual)
    .css('width', valorAtualizadoDaBarraDeProgresso + stringPercentual);
}

function mostrarSplash(){
  $('.splash').css('display', 'block');
}
function escondeSplash(){
  $('.splash').css('display', 'none');
}