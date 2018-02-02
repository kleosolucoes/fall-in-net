function kleo(action, id) {
  $(".splash").css("display", "block");
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
  $(".splash").css("display", "block");
  location.href = url;
}

function submeterFormulario(form) {
  var temErros = false;
  var inputs = form.elements;
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
    $(".splash").css("display", "block");
    form.submit();
  }
}

function isEmail(email) {
  er = /^[a-zA-Z0-9][a-zA-Z0-9\._-]+@([a-zA-Z0-9\._-]+\.)[a-zA-Z-0-9]{2,3}$/;
  if (!er.exec(email)) {
    return false;
  } else {
    return true;
  }
}

$(window).bind("load", function () {
  // Remove splash screen after load
  $('.splash').css('display', 'none');


  if($('#ancora').offset()){
    targetOffset = $('#ancora').offset().top;

    $('html, body').animate({ 
      scrollTop: targetOffset - 100
    }, 1000); 
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