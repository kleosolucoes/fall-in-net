<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Doctrine\ORM\EntityManager;
use Exception;

/**
 * Nome: RepositorioORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso ao repositorio ORM
 */
class RepositorioORM {

    private $_doctrineORMEntityManager;
    private $_pessoaORM;
    private $_entidadeORM;
    private $_entidadeTipoORM;
    private $_grupoORM;
    private $_grupoPessoaORM;
    private $_grupoPessoaTipoORM;
    private $_eventoORM;
    private $_eventoCelulaORM;
    private $_grupoEventoORM;
    private $_eventoTipoORM;
    private $_hierarquiaORM;
    private $_turmaAlunoORM;
    private $_pessoaHierarquiaORM;
    private $_grupoResponsavelORM;
    private $_grupoPaiFilhoORM;
    private $_grupoAtendimentoORM;
    private $_eventoFrequenciaORM;
    private $_fatoCicloORM;
    private $_fatoCelulaORM;
    private $_fatoLiderORM;
    private $_dimensaoORM;
    private $_dimensaoTipoORM;
    private $_grupoCvORM;
    private $_turmaORM;
    private $_solicitacaoORM;
    private $_solicitacaoTipoORM;
    private $_cursoORM;
    private $_disciplinaORM;
    private $_aulaORM;
    private $_solicitacaoSituacaoORM;
    private $_situacaoORM;
    private $_fatoRankingORM;

    /**
     * Contrutor
     */
    public function __construct(EntityManager $doctrineORMEntityManager = null) {
        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }
    }

    /**
     * Metodo public para obter a instancia do PessoaORM
     * @return PessoaORM
     */
    public function getPessoaORM() {
        if (is_null($this->_pessoaORM)) {
            $this->_pessoaORM = new PessoaORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\Pessoa');
        }
        return $this->_pessoaORM;
    }

    /**
     * Metodo public para obter a instancia do EntidadeORM
     * @return CircuitoORM
     */
    public function getEntidadeORM() {
        if (is_null($this->_entidadeORM)) {
            $this->_entidadeORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_ENTIDADE);
        }
        return $this->_entidadeORM;
    }

    /**
     * Metodo public para obter a instancia do EntidadeTipoORM
     * @return CircuitoORM
     */
    public function getEntidadeTipoORM() {
        if (is_null($this->_entidadeTipoORM)) {
            $this->_entidadeTipoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_ENTIDADE_TIPO);
        }
        return $this->_entidadeTipoORM;
    }

    /**
     * Metodo public para obter a instancia do GrupoPessoaTipoORM
     * @return GrupoPessoaTipoORM
     */
    public function getGrupoPessoaTipoORM() {
        if (is_null($this->_grupoPessoaTipoORM)) {
            $this->_grupoPessoaTipoORM = new GrupoPessoaTipoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_PESSOA_TIPO);
        }
        return $this->_grupoPessoaTipoORM;
    }

    /**
     * Metodo public para obter a instancia do GrupoPessoaORM
     * @return GrupoPessoaORM
     */
    public function getGrupoPessoaORM() {
        if (is_null($this->_grupoPessoaORM)) {
            $this->_grupoPessoaORM = new GrupoPessoaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_PESSOA);
        }
        return $this->_grupoPessoaORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return CircuitoORM
     */
    public function getGrupoResponsavelORM() {
        if (is_null($this->_grupoResponsavelORM)) {
            $this->_grupoResponsavelORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_RESPONSAVEL);
        }
        return $this->_grupoResponsavelORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return CircuitoORM
     */
    public function getGrupoPaiFilhoORM() {
        if (is_null($this->_grupoPaiFilhoORM)) {
            $this->_grupoPaiFilhoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_PAI_FILHO);
        }
        return $this->_grupoPaiFilhoORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return GrupoORM
     */
    public function getGrupoORM() {
        if (is_null($this->_grupoORM)) {
            $this->_grupoORM = new GrupoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO);
        }
        return $this->_grupoORM;
    }

    /**
     * Metodo public para obter a instancia do EventoORM
     * @return CircuitoORM
     */
    public function getEventoORM() {
        if (is_null($this->_eventoORM)) {
            $this->_eventoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_EVENTO);
        }
        return $this->_eventoORM;
    }

    /**
     * Metodo public para obter a instancia do EventoCelulaORM
     * @return CircuitoORM
     */
    public function getEventoCelulaORM() {
        if (is_null($this->_eventoCelulaORM)) {
            $this->_eventoCelulaORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_EVENTO_CELULA);
        }
        return $this->_eventoCelulaORM;
    }

    /**
     * Metodo public para obter a instancia do GrupoEventoORM
     * @return CircuitoORM
     */
    public function getGrupoEventoORM() {
        if (is_null($this->_grupoEventoORM)) {
            $this->_grupoEventoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_EVENTO);
        }
        return $this->_grupoEventoORM;
    }

    /**
     * Metodo public para obter a instancia do GrupoAtendimentoORM
     * @return CircuitoORM
     */
    public function getGrupoAtendimentoORM() {
        if (is_null($this->_grupoAtendimentoORM)) {
            $this->_grupoAtendimentoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_ATENDIMENTO);
        }
        return $this->_grupoAtendimentoORM;
    }

    /**
     * Metodo public para obter a instancia do EventoTipoORM
     * @return CircuitoORM
     */
    public function getEventoTipoORM() {
        if (is_null($this->_eventoTipoORM)) {
            $this->_eventoTipoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_EVENTO_TIPO);
        }
        return $this->_eventoTipoORM;
    }

    /**
     * Metodo public para obter a instancia do HierarquiaORM
     * @return HierarquiaORM
     */
    public function getHierarquiaORM() {
        if (is_null($this->_hierarquiaORM)) {
            $this->_hierarquiaORM = new HierarquiaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_HIERAQUIA);
        }
        return $this->_hierarquiaORM;
    }

    /**
     * Metodo public para obter a instancia do PessoaHierarquiaORM
     * @return CircuitoORM
     */
    public function getPessoaHierarquiaORM() {
        if (is_null($this->_pessoaHierarquiaORM)) {
            $this->_pessoaHierarquiaORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_PESSOA_HIERAQUIA);
        }
        return $this->_pessoaHierarquiaORM;
    }

    /**
     * Metodo public para obter a instancia do EventoTipoORM
     * @return CircuitoORM
     */
    public function getTurmaAlunoORM() {
        if (is_null($this->_turmaAlunoORM)) {
            $this->_turmaAlunoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_TURMA_ALUNO);
        }
        return $this->_turmaAlunoORM;
    }

    /**
     * Metodo public para obter a instancia do EventoTipoORM
     * @return CircuitoORM
     */
    public function getEventoFrequenciaORM() {
        if (is_null($this->_eventoFrequenciaORM)) {
            $this->_eventoFrequenciaORM = new EventoFrequenciaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_EVENTO_FREQUENCIA);
        }
        return $this->_eventoFrequenciaORM;
    }

    /**
     * Metodo public para obter a instancia do FatoCicloORM
     * @return FatoCicloORM
     */
    public function getFatoCicloORM() {
        if (is_null($this->_fatoCicloORM)) {
            $this->_fatoCicloORM = new FatoCicloORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_CICLO);
        }
        return $this->_fatoCicloORM;
    }

    /**
     * Metodo public para obter a instancia do FatoCelulaORM
     * @return FatoCelulaORM
     */
    public function getFatoCelulaORM() {
        if (is_null($this->_fatoCelulaORM)) {
            $this->_fatoCelulaORM = new FatoCelulaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_CELULA);
        }
        return $this->_fatoCelulaORM;
    }

    /**
     * Metodo public para obter a instancia do FatoLiderORM
     * @return FatoLiderORM
     */
    public function getFatoLiderORM() {
        if (is_null($this->_fatoLiderORM)) {
            $this->_fatoLiderORM = new FatoLiderORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_LIDER);
        }
        return $this->_fatoLiderORM;
    }

    /**
     * Metodo public para obter a instancia do DimensaoTipoORM
     * @return CircuitoORM
     */
    public function getDimensaoORM() {
        if (is_null($this->_dimensaoORM)) {
            $this->_dimensaoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_DIMENSAO);
        }
        return $this->_dimensaoORM;
    }

    /**
     * Metodo public para obter a instancia do DimensaoTipoORM
     * @return CircuitoORM
     */
    public function getDimensaoTipoORM() {
        if (is_null($this->_dimensaoTipoORM)) {
            $this->_dimensaoTipoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_DIMENSAO_TIPO);
        }
        return $this->_dimensaoTipoORM;
    }

    /**
     * Metodo public para obter a instancia do DimensaoTipoORM
     * @return CircuitoORM
     */
    public function getGrupoCvORM() {
        if (is_null($this->_grupoCvORM)) {
            $this->_grupoCvORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_CV);
        }
        return $this->_grupoCvORM;
    }

    /**
     * Metodo public para obter a instancia do CursoORM
     * @return CircuitoORM
     */
    public function getCursoORM() {
        if (is_null($this->_cursoORM)) {
            $this->_cursoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_CURSO);
        }
        return $this->_cursoORM;
    }

    /**
     * Metodo public para obter a instancia do DisciplinaORM
     * @return DisciplinaORM
     */
    public function getDisciplinaORM() {
        if (is_null($this->_disciplinaORM)) {
            $this->_disciplinaORM = new DisciplinaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_DISCIPLINA);
        }
        return $this->_disciplinaORM;
    }

    /**
     * Metodo public para obter a instancia do AulaORM
     * @return CircuitoORM
     */
    public function getAulaORM() {
        if (is_null($this->_aulaORM)) {
            $this->_aulaORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_AULA);
        }
        return $this->_aulaORM;
    }

    /**
     * Metodo public para obter a instancia do TurmaORM
     * @return TurmaORM
     */
    public function getTurmaORM() {
        if (is_null($this->_turmaORM)) {
            $this->_turmaORM = new TurmaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_TURMA);
        }
        return $this->_turmaORM;
    }

    /**
     * Metodo public para obter a instancia do SolicitacaoORM
     * @return SolicitacaoORM
     */
    public function getSolicitacaoORM() {
        if (is_null($this->_solicitacaoORM)) {
            $this->_solicitacaoORM = new SolicitacaoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_SOLICITACAO);
        }
        return $this->_solicitacaoORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return CircuitoORM
     */
    public function getSolicitacaoTipoORM() {
        if (is_null($this->_solicitacaoTipoORM)) {
            $this->_solicitacaoTipoORM = new SolicitacaoTipoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_SOLICITACAO_TIPO);
        }
        return $this->_solicitacaoTipoORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return CircuitoORM
     */
    public function getSolicitacaoSituacaoORM() {
        if (is_null($this->_solicitacaoSituacaoORM)) {
            $this->_solicitacaoSituacaoORM = new SolicitacaoTipoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_SOLICITACAO_SITUACAO);
        }
        return $this->_solicitacaoSituacaoORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return CircuitoORM
     */
    public function getSituacaoORM() {
        if (is_null($this->_situacaoORM)) {
            $this->_situacaoORM = new SolicitacaoTipoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_SITUACAO);
        }
        return $this->_situacaoORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return CircuitoORM
     */
    public function getFatoRankingORM() {
        if (is_null($this->_fatoRankingORM)) {
            $this->_fatoRankingORM = new FatoRankingORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_RANKING);
        }
        return $this->_fatoRankingORM;
    }

    /**
     * Metodo public para obter a instancia EntityManager com acesso ao banco de dados
     * @return EntityManager
     */
    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

    /**
     * Iniciar transação
     */
    public function iniciarTransacao() {
        try {
            $this->getDoctrineORMEntityManager()->beginTransaction();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Fechar transação
     */
    public function fecharTransacao() {
        try {
            $this->getDoctrineORMEntityManager()->commit();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Desfazer transação
     */
    public function desfazerTransacao() {
        try {
            $this->getDoctrineORMEntityManager()->rollback();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
