<?php

namespace Application\Model\Entity;

/**
 * Nome: Pessoa.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela pessoa
 */
use Application\Controller\Helper\Funcoes;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Entidade\Entity\Entidade;
use Entidade\Entity\EventoFrequencia;
use Entidade\Entity\GrupoPessoa;
use Entidade\Entity\GrupoResponsavel;
use Entidade\Entity\PessoaHierarquia;
use Entidade\Entity\TurmaAluno;
use Exception;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/** @ORM\Entity */
class Pessoa extends CircuitoEntity implements InputFilterAwareInterface {

    protected $inputFilter;
    protected $inputFilterPessoaFrequencia;

    /**
     * @ORM\OneToMany(targetEntity="GrupoResponsavel", mappedBy="pessoa") 
     */
    protected $grupoResponsavel;

    /**
     * @ORM\OneToMany(targetEntity="TurmaAluno", mappedBy="pessoa") 
     */
    protected $turmaAluno;

    /**
     * @ORM\OneToMany(targetEntity="EventoFrequencia", mappedBy="pessoa") 
     */
    protected $eventoFrequencia;

    /**
     * @ORM\OneToMany(targetEntity="GrupoPessoa", mappedBy="pessoa") 
     */
    protected $grupoPessoa;

    /**
     * @ORM\OneToMany(targetEntity="PessoaHierarquia", mappedBy="pessoa") 
     */
    protected $pessoaHierarquia;

    /**
     * @ORM\OneToMany(targetEntity="Curso", mappedBy="pessoa") 
     */
    protected $curso;

    /**
     * @ORM\OneToMany(targetEntity="Solicitacao", mappedBy="pessoa") 
     */
    protected $solicitacao;

    public function __construct() {
        $this->turmaAluno = new ArrayCollection();
        $this->grupoResponsavel = new ArrayCollection();
        $this->eventoFrequencia = new ArrayCollection();
        $this->grupoPessoa = new ArrayCollection();
        $this->pessoaHierarquia = new ArrayCollection();
        $this->curso = new ArrayCollection();
        $this->solicitacao = new ArrayCollection();
        $this->setAtualizar_dados('S');
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="integer") */
    protected $telefone;

    /** @ORM\Column(type="string") */
    protected $email;

    /** @ORM\Column(type="string") */
    protected $senha;

    /** @ORM\Column(type="string") */
    protected $data_nascimento;

    /** @ORM\Column(type="string") */
    protected $documento;

    /** @ORM\Column(type="string") */
    protected $token;

    /** @ORM\Column(type="string") */
    protected $token_data;

    /** @ORM\Column(type="string") */
    protected $token_hora;

    /** @ORM\Column(type="string") */
    protected $data_revisao;

    /** @ORM\Column(type="string") */
    protected $foto;

    /** @ORM\Column(type="string") */
    protected $sexo;

    /** @ORM\Column(type="string") */
    protected $atualizar_dados;

    public function exchangeArray($data) {
        $this->nome = (!empty($data['nome']) ? strtoupper($data['nome']) : null);
    }

    protected $tipo;
    protected $transferido;
    protected $dataTransferido;
    protected $dataInativacao;
    protected $idGrupoPessoa;
    protected $ativo;
    protected $matriculaAtual;

    /**
     * Recupera as Responsabilidades ativas
     * @return Entidade[]
     */
    function getResponsabilidadesAtivas($todasResposabilidades = false) {
        $responsabilidadesAtivas = null;
        /* Responsabilidades */
        $responsabilidadesTodosStatus = $this->getGrupoResponsavel();

        if ($responsabilidadesTodosStatus) {
            /* Verificar responsabilidades ativas */
            foreach ($responsabilidadesTodosStatus as $responsabilidadeTodosStatus) {
                if ($todasResposabilidades) {
                    $responsabilidadesAtivas[] = $responsabilidadeTodosStatus;
                }
                if ($responsabilidadeTodosStatus->verificarSeEstaAtivo() && !$todasResposabilidades) {
                    $responsabilidadesAtivas[] = $responsabilidadeTodosStatus;
                }
            }
        }
        /* Ordenando */
        if ($responsabilidadesAtivas) {
            for ($i = 0; $i < count($responsabilidadesAtivas); $i++) {
                for ($j = 1; $j < count($responsabilidadesAtivas); $j++) {
                    $r[1] = $responsabilidadesAtivas[$i];
                    $tipo[1] = $r[1]->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId();

                    $r[2] = $responsabilidadesAtivas[$j];
                    $tipo[2] = $r[2]->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId();

                    if ($tipo[1] < $tipo[2]) {
                        $responsabilidadesAtivas[$j] = $r[1];
                        $responsabilidadesAtivas[$i] = $r[2];
                    }
                }
            }
        }
        return $responsabilidadesAtivas;
    }

    /**
     * Retorna o primeiro e ultimo nome da pessoa
     * @return String
     */
    function getNomePrimeiroUltimo() {
        $explodeNome = explode(" ", $this->getNome());
        $primeiroNome = $explodeNome[0];
        if (count($explodeNome) > 1) {
            $primeiroNome .= ' ' . $explodeNome[(count($explodeNome) - 1)];
        }
        return $primeiroNome;
    }

    /**
     * Retorna o primeiro nome da pessoa
     * @return String
     */
    function getNomePrimeiro() {
        $explodeNome = explode(" ", $this->getNome());
        $primeiroNome = $explodeNome[0];
        return $primeiroNome;
    }

    /**
     * Retorna o primeiro e a sigla do ultimo nome da pessoa
     * @return String
     */
    function getNomePrimeiroPrimeiraSiglaUltimo() {
        $explodeNome = explode(" ", $this->getNome());
        $primeiroNome = $explodeNome[0];
        $ultimoNome = substr($explodeNome[(count($explodeNome) - 1)], 0, 1);
        return $primeiroNome . '&nbsp;' . $ultimoNome . '.';
    }

    /**
     * Retorna o nome formatado em relação a quantidade de eventos no ciclo
     * @param int $tipo
     * @return String
     */
    function getNomeListaDeLancamento($tipo = 0) {
        $nome = '';
        switch ($tipo) {
            case 1:
                if (strlen($this->getNome()) > 28) {
                    $nome = substr($this->getNome(), 0, 26) . '..';
                } else {
                    $nome = $this->getNome();
                }
                break;
            case 2:
                if (strlen($this->getNome()) > 20) {
                    $nome = substr($this->getNome(), 0, 18) . '..';
                } else {
                    $nome = $this->getNome();
                }
                break;
            case 3:
                if (strlen($this->getNome()) > 15) {
                    $nome = substr($this->getNome(), 0, 13) . '..';
                } else {
                    $nome = $this->getNome();
                }
                break;
            default:
                if (strlen($this->getNome()) > 8) {
                    $nome = substr($this->getNome(), 0, 8) . '..';
                } else {
                    $nome = $this->getNome();
                }

                break;
        }

        return $nome;
    }

    function getEventoFrequenciasFiltradosPorEvento($idEvento) {
        $criteria = Criteria::create()
                ->andWhere(Criteria::expr()->eq("evento_id", (int) $idEvento))
        ;
        return $this->getEventoFrequencia()->matching($criteria);
    }

    function getEventoFrequenciaFiltradoPorEventoEDia($idEvento, $diaRealDoEvento) {
        $eventoSelecionado = null;
        $eventosFrequenciaFiltrados = $this->getEventoFrequenciasFiltradosPorEvento($idEvento);
        if ($eventosFrequenciaFiltrados->count() > 0) {
            foreach ($eventosFrequenciaFiltrados as $eventosFrequenciaFiltrado) {
                if ($eventosFrequenciaFiltrado->getDia()->format('Y-m-d') == $diaRealDoEvento) {
                    $eventoSelecionado = $eventosFrequenciaFiltrado;
                    break;
                }
            }
        }
        return $eventoSelecionado;
    }

    /**
     * Verificar se esta transferido ou nao
     * @param type $mes
     * @param type $ano
     * @return boolean
     */
    public function verificarSeFoiTransferido($mes, $ano, $tipo = 0) {
        $resposta = false;
        if ($tipo == 0) {
            if ($this->getTransferido() == 'S' && $this->getDataTransferidoMes() == $mes && $this->getDataTransferidoAno() && $ano) {
                $resposta = true;
            } else {
                if (!$this->getAtivo()) {
                    if ($this->getTransferido() == 'S' && $this->getDataInativacaoMes() == $mes && $this->getDataInativacaoAno() && $ano) {
                        $resposta = true;
                    }
                }
            }
        }
        if ($tipo == 1) {
            if ($this->getTransferido() == 'S' && $this->getDataTransferidoMes() == $mes && $this->getDataTransferidoAno() && $ano) {
                $resposta = true;
            }
        }
        if ($tipo == 2) {
            if ($this->getTransferido() == 'S' && $this->getDataInativacaoMes() == $mes && $this->getDataInativacaoAno() && $ano) {
                $resposta = true;
            }
        }
        return $resposta;
    }

    /**
     * Verificar se te alguma responsabilidade que foi inativada no mes informado
     * @param String $data
     * @return GrupoResponsavel
     */
    public function verificarSeTemAlgumaResponsabilidadeInativadoNaDataInformado($data) {
        $grupoResponsavel = null;
        foreach ($this->getGrupoResponsavel() as $gr) {
            if (!$gr->verificarSeEstaAtivo() && $gr->getData_inativacao() == $data) {
                $grupoResponsavel = $gr;
                break;
            }
        }

        return $grupoResponsavel;
    }

    /**
     * Verificar se te alguma responsabilidade ativa
     * @return boolean
     */
    public function verificarSeTemAlgumaResponsabilidadeAtiva() {
        $resposta = false;
        foreach ($this->getGrupoResponsavel() as $gr) {
            if ($gr->verificarSeEstaAtivo()) {
                $resposta = true;
                break;
            }
        }
        return $resposta;
    }

    function getNome() {
        return $this->nome;
    }

    function getEmail() {
        return $this->email;
    }

    function getSenha() {
        return $this->senha;
    }

    function getData_nascimento() {
        return $this->data_nascimento;
    }

    function getData_nascimentoFormatada() {
        return Funcoes::mudarPadraoData($this->getData_nascimento(), 1);
    }

    function getDocumento() {
        return $this->documento;
    }

    function setNome($nome) {
        $this->nome = trim(strtoupper($nome));
    }

    function setEmail($email) {
        $this->email = trim(strtolower($email));
    }

    function setSenha($senha, $adicionarMD5 = true) {
        $senhaAjustada = $senha;
        if ($adicionarMD5) {
            $senhaAjustada = md5($senha);
        }
        $this->senha = $senhaAjustada;
    }

    function setData_nascimento($data_nascimento) {
        $this->data_nascimento = $data_nascimento;
    }

    function setDocumento($documento) {
        $this->documento = $documento;
    }

    function getToken() {
        return $this->token;
    }

    /**
     * Seta token e data para validacao
     * @param String $token
     */
    function setToken($token) {
        $this->token = $token;
        $timeNow = new DateTime();
        $this->setToken_data($timeNow->format('Y-m-d'));
        $this->setToken_hora($timeNow->format('H:s:i'));
    }

    /**
     * Gera um token com data e hora atual em md5
     * @return String
     */
    function gerarToken($tipo = 0) {
        $timeNow = new DateTime();
        $dataEnvio = $timeNow->format('Ymd');
        $hora = $timeNow->format('His');
        $token = md5($dataEnvio . $hora . $tipo);
        return $token;
    }

    function getToken_data() {
        return $this->token_data;
    }

    function getToken_data_ano() {
        return substr($this->token_data, 0, 4);
    }

    function getToken_data_mes() {
        return substr($this->token_data, 5, 2);
    }

    function getToken_data_dia() {
        return substr($this->token_data, 8, 2);
    }

    function setToken_data($token_data) {
        $this->token_data = $token_data;
    }

    function getToken_hora() {
        return $this->token_hora;
    }

    function getToken_hora_hora() {
        return substr($this->token_hora, 0, 2);
    }

    function getToken_hora_minutos() {
        return substr($this->token_hora, 3, 2);
    }

    function getToken_hora_segundos() {
        return substr($this->token_hora, 6, 2);
    }

    function setToken_hora($token_hora) {
        $this->token_hora = $token_hora;
    }

    /**
     * Retorna as responsailidades da pessoa
     * @return GrupoResponsavel
     */
    function getGrupoResponsavel() {
        return $this->grupoResponsavel;
    }

    function setGrupoResponsavel($grupoResponsavel) {
        $this->grupoResponsavel = $grupoResponsavel;
    }

    function getTelefone() {
        return $this->telefone;
    }

    function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    /**
     * Retorna os eventoFrequencia
     * @return EventoFrequencia
     */
    function getEventoFrequencia() {
        return $this->eventoFrequencia;
    }

    function setEventoFrequencia($eventoFrequencia) {
        $this->eventoFrequencia = $eventoFrequencia;
    }

    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    /**
     * Retorna a string com o nome do arquivo da foto.
     * @return String
     */
    function getFoto() {
        return $this->foto;
    }

    function setFoto($foto) {
        $this->foto = $foto;
    }

    function getTransferido() {
        return $this->transferido;
    }

    function setTransferido($transferido, $dataTransferencia, $dataInativacao) {
        $this->transferido = $transferido;
        $this->setDataTransferido($dataTransferencia);
        $this->setDataInativacao($dataInativacao);
    }

    function getDataTransferido() {
        return $this->dataTransferido;
    }

    function setDataTransferido($dataTransferido) {
        $this->dataTransferido = $dataTransferido;
    }

    function getDataTransferidoAno() {
        return explode('-', $this->getDataTransferido())[0];
    }

    function getDataTransferidoMes() {
        return explode('-', $this->getDataTransferido())[1];
    }

    function getDataTransferidoDia() {
        return explode('-', $this->getDataTransferido())[2];
    }

    function getIdGrupoPessoa() {
        return $this->idGrupoPessoa;
    }

    function setIdGrupoPessoa($idGrupoPessoa) {
        $this->idGrupoPessoa = $idGrupoPessoa;
    }

    function getAtivo() {
        return $this->ativo;
    }

    function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

    function getDataInativacao() {
        return $this->dataInativacao;
    }

    function setDataInativacao($dataInativacao) {
        $this->dataInativacao = $dataInativacao;
    }

    function getData_revisao() {
        return $this->data_revisao;
    }

    function setData_revisao($data_revisao) {
        $this->data_revisao = $data_revisao;
    }

    function getTurmaAluno() {
        return $this->turmaAluno;
    }

    /**
     * Retorna a turma aluno ativo
     * @return TurmaAluno
     */
    function getTurmaAlunoAtivo() {
        $turmaAlunoAtiva = null;
        foreach ($this->getTurmaAluno() as $ta) {
            if ($ta->verificarSeEstaAtivo()) {
                $turmaAlunoAtiva = $ta;
                break;
            }
        }

        return $turmaAlunoAtiva;
    }

    /**
     * Retorna a pessoa hierarquia ativo
     * @return PessoaHierarquia
     */
    function getPessoaHierarquiaAtivo() {
        $pessoaHierarquiaAtiva = null;
        foreach ($this->getPessoaHierarquia() as $ph) {
            if ($ph->verificarSeEstaAtivo()) {
                $pessoaHierarquiaAtiva = $ph;
                break;
            }
        }
        return $pessoaHierarquiaAtiva;
    }

    /**
     * Retorna o grupo pessoa ativo
     * @return GrupoPessoa
     */
    function getGrupoPessoaAtivo() {
        $grupoPessoaAtiva = null;
        foreach ($this->getGrupoPessoa() as $gp) {
            if ($gp->verificarSeEstaAtivo()) {
                $grupoPessoaAtiva = $gp;
                break;
            }
        }
        return $grupoPessoaAtiva;
    }

    /**
     * Varifica se a pessoa participou de algum revisao
     * @return boolean
     */
    function verificaSeParticipouDoRevisao() {
        $resposta = false;
        $eventosFrequencia = $this->getEventoFrequencia();
        foreach ($eventosFrequencia as $frequencia) {
            if ($frequencia->getEvento()->getEventoTipo()->getId() == EventoTipo::tipoRevisao) {
                $resposta = true;
            }
        }
        return $resposta;
    }

    function setTurmaAluno($turmaAluno) {
        $this->turmaAluno = $turmaAluno;
    }

    /**
     * Retorna o GrupoPessoa
     * @return GrupoPessoa
     */
    function getGrupoPessoa() {
        return $this->grupoPessoa;
    }

    function setGrupoPessoa($grupoPessoa) {
        $this->grupoPessoa = $grupoPessoa;
    }

    public function getIdade() {
        $idade = 0;
        if ($this->getData_nascimento()) {
            // Separa em dia, mês e ano
            list($ano, $mes, $dia) = explode('-', $this->getData_nascimento());

            // Descobre que dia é hoje e retorna a unix timestamp
            $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            // Descobre a unix timestamp da data de nascimento
            $nascimento = mktime(0, 0, 0, $mes, $dia, $ano);

            // Depois apenas fazemos o cálculo
            $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
        }
        return $idade;
    }

    public function getDataNascimentoFormatada() {
        $resposta = '';
        if ($this->getData_nascimento()) {
            $resposta = Funcoes::mudarPadraoData($this->getData_nascimento(), 1);
        }
        return $resposta;
    }

    public function getInputFilter() {
        
    }

    public function getInputFilterPessoaFrequencia() {
        if (!$this->inputFilterPessoaFrequencia) {
            $inputFilter = new InputFilter();
            $inputFilter->add(array(
                'name' => 'nome',
                'required' => true,
                'filter' => array(
                    array('name' => 'StripTags'), // removel xml e html string
                    array('name' => 'StringTrim'), // removel espaco do inicio e do final da string
                    array('name' => 'StringToUpper'), // transforma em maiusculo
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 80,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name' => 'ddd',
                'required' => true,
                'filter' => array(
                    array('name' => 'StripTags'), // removel xml e html string
                    array('name' => 'StringTrim'), // removel espaco do inicio e do final da string
                    array('name' => 'Int'), // transforma string para inteiro
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 2,
                            'max' => 2,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name' => 'telefone',
                'required' => true,
                'filter' => array(
                    array('name' => 'StripTags'), // removel xml e html string
                    array('name' => 'StringTrim'), // removel espaco do inicio e do final da string
                    array('name' => 'Int'), // transforma string para inteiro
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 8, # xx xxxx-xxxx
                            'max' => 9, # xx xxxx-xxxxx
                        ),
                    ),
                ),
            ));
            $this->inputFilterPessoaFrequencia = $inputFilter;
        }
        return $this->inputFilterPessoaFrequencia;
    }

    /**
     * @param InputFilterInterface $inputFilter
     * @throws Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception("Nao utilizado");
    }

    /**
     * Retorna array das hierarquias
     * @return PessoaHierarquia
     */
    function getPessoaHierarquia() {
        return $this->pessoaHierarquia;
    }

    function setPessoaHierarquia($pessoaHierarquia) {
        $this->pessoaHierarquia = $pessoaHierarquia;
    }

    function getSexo() {
        return $this->sexo;
    }

    function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    function getMatriculaAtual() {
        return $this->matriculaAtual;
    }

    function setMatriculaAtual($matriculaAtual) {
        $this->matriculaAtual = $matriculaAtual;
    }

    function getAtualizar_dados() {
        return $this->atualizar_dados;
    }

    function setAtualizar_dados($atualizar_dados) {
        $this->atualizar_dados = $atualizar_dados;
    }

    function dadosAtualizados() {
        $this->setAtualizar_dados('N');
    }

    function setPrecisaAtualizarDados() {
        $this->setAtualizar_dados('S');
    }

    function getCurso() {
        return $this->curso;
    }

    function setCurso($curso) {
        $this->curso = $curso;
    }

    function getSolicitacao() {
        return $this->solicitacao;
    }

    function setSolicitacao($solicitacao) {
        $this->solicitacao = $solicitacao;
    }

}
