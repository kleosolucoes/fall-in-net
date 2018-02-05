function validacoesFormulario(campo){
  var temErro = false;
  var mensagemDeErro = '';
  if(campo.id == 'inputNome' || campo.id == 'inputNomePonte' || campo.id == 'inputNomeProspecto'){
    if(campo.value.length < 3 || campo.value.length > 50){
      temErro = true;
      mensagemDeErro = 'Nome precisa ter 3 a 50 caracteres';
    }
  }
  if(campo.id == 'inputTelefone' || campo.id == 'inputTelefonePonte' || campo.id == 'inputTelefoneProspecto'){
    if(campo.value.length < 10 || campo.value.length > 11){
      temErro = true;
      mensagemDeErro = 'Telefone precisa ter 10 ou 11 caracteres';
    }
  }
  switch(campo.id){
    case 'inputEmail':
      if(!isEmail(campo.value)){
        temErro = true;
        mensagemDeErro = 'Preencha o email corretamente';
      }
      break;
    case 'inputRepetirEmail':
      if(campo.value.length === 0 || campo.value != document.getElementById('inputEmail').value){
        temErro = true;
        mensagemDeErro = 'Repita o email corretamente';
      }
      break;   
    case 'inputSenha':
      if(campo.value.length === 0){
        temErro = true;
        mensagemDeErro = 'Preencha a senha';
      }
      break;
    case 'inputRepetirSenha':
      if(campo.value.length === 0 || campo.value != document.getElementById('inputSenha').value){
        temErro = true;
        mensagemDeErro = 'Repita a senha';
      }
      break;
    case 'inputDocumento':
      if(campo.value.length === 0 || !isCPF(campo.value)){
        temErro = true;
        mensagemDeErro = 'Preencha o CPF corretamente';
      }
      break;
    case 'inputDia':
      if(parseInt(campo.value) === 0){
        temErro = true;
        mensagemDeErro = 'Selecione o Dia';
      }
      break;
    case 'inputMes':
      if(parseInt(campo.value) === 0){
        temErro = true;
        mensagemDeErro = 'Selecione o Mês';
      }
      break;
    case 'inputAno':
      if(parseInt(campo.value) === 0){
        temErro = true;
        mensagemDeErro = 'Selecione o Ano';
      }
      break;
    case 'inputSexo':
      if(parseInt(campo.value) === 0){
        temErro = true;
        mensagemDeErro = 'Selecione o Sexo';
      }
      break;
    default: 
      break;
  }
  if(temErro){
    escreveMensagemDeErro(campo.id, mensagemDeErro);
    return true;
  }else{
    limpaAMensagemDeErro(campo.id);
    return false;
  }

}

function escreveMensagemDeErro(id, mensagem){
  var html = '<p class="text-danger"><small>' +
      mensagem +
      '</small></p>';
  var idDiv = 'mensagemErro' + id;
  document.getElementById(idDiv).innerHTML = html;
  document.getElementById(id).classList.remove("is-valid");
  document.getElementById(id).classList.add("is-invalid");
}

function limpaAMensagemDeErro(id){
  var html = '';
  var idDiv = 'mensagemErro' + id;
  document.getElementById(idDiv).innerHTML = html;
  document.getElementById(id).classList.add("is-valid");
  document.getElementById(id).classList.remove("is-invalid");
}