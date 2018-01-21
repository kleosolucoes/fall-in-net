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
    private $_grupoORM;
    private $_grupoPessoaORM;
    private $_grupoPessoaTipoORM;
    private $_eventoORM;
    private $_grupoEventoORM;
    private $_eventoTipoORM;
    private $_hierarquiaORM;
    private $_pessoaHierarquiaORM;
    private $_grupoResponsavelORM;
    private $_grupoPaiFilhoORM;
    private $_eventoFrequenciaORM;
    private $_tarefaTipoORM;
    private $_tarefaORM;

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
     * Metodo public para obter a instancia do GrupoPessoaTipoORM
     * @return GrupoPessoaTipoORM
     */
    public function getGrupoPessoaTipoORM() {
        if (is_null($this->_grupoPessoaTipoORM)) {
            $this->_grupoPessoaTipoORM = new GrupoPessoaTipoORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\GrupoPessoaTipo');
        }
        return $this->_grupoPessoaTipoORM;
    }

    /**
     * Metodo public para obter a instancia do GrupoPessoaORM
     * @return GrupoPessoaORM
     */
    public function getGrupoPessoaORM() {
        if (is_null($this->_grupoPessoaORM)) {
            $this->_grupoPessoaORM = new GrupoPessoaORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\GrupoPessoa');
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
            $this->_eventoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\Evento');
        }
        return $this->_eventoORM;
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
    public function getEventoFrequenciaORM() {
        if (is_null($this->_eventoFrequenciaORM)) {
            $this->_eventoFrequenciaORM = new EventoFrequenciaORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\EventoFrequencia');
        }
        return $this->_eventoFrequenciaORM;
    }

    /**
     * Metodo public para obter a instancia do TarefaTipoORM
     * @return CircuitoORM
     */
    public function getTarefaTipoORM() {
        if (is_null($this->_tarefaTipoORM)) {
            $this->_tarefaTipoORM = new KleoORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\TarefaTipo');
        }
        return $this->_tarefaTipoORM;
    }
  
    /**
     * Metodo public para obter a instancia do TarefaORM
     * @return CircuitoORM
     */
    public function getTarefaORM() {
        if (is_null($this->_tarefaORM)) {
            $this->_tarefaORM = new KleoORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\Tarefa');
        }
        return $this->_tarefaORM;
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
