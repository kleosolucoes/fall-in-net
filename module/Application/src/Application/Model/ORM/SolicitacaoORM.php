<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\Solicitacao;
use Application\Model\ORM\CircuitoORM;
use Exception;

/**
 * Nome: SolicitacaoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity solicitacao
 */
class SolicitacaoORM extends CircuitoORM {

    /**
     * Retorna todas as solicitações pela data de criação
     * @param type $dataDeCriacao
     * @return Solicitacao[]
     * @throws Exception
     */
    public function encontrarTodosPorDataDeCriacao($dataDeCriacaoInicial, $dataDeCriacaoFinal) {
        $dql = "SELECT "
                . " s.id, s.objeto1, s.objeto2, s.numero, s.nome "
                . "FROM  " . Constantes::$ENTITY_SOLICITACAO . " s "
                . "WHERE "
                . "s.data_criacao >= ?1 AND s.data_criacao <= ?2";
        try {
            $result = $this->getEntityManager()->createQuery($dql)
                    ->setParameter(1, $dataDeCriacaoInicial)
                    ->setParameter(2, $dataDeCriacaoFinal)
                    ->getResult();
            return $result;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
