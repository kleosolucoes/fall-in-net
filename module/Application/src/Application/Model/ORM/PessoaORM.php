<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\Pessoa;
use Doctrine\Common\Collections\Criteria;
use Exception;

/**
 * Nome: PessoaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity pessoa
 */
class PessoaORM extends CircuitoORM {

    /**
     * Localizar pessoa por email
     * 
     * @param String $email
     * @return Pessoa
     * @throws Exception
     */
    public function encontrarPorEmail($email) {
        try {
            $pessoa = $this->getEntityManager()
                    ->getRepository($this->getEntity())
                    ->findOneBy(array(Constantes::$ENTITY_PESSOA_EMAIL => $email));
            return $pessoa;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * Localizar pessoa por nome
     */
    public function encontrarPorNome($nome) {
        try {
            $dql = "SELECT p.id, p.nome, p.documento, p.email, p.senha "
                    . "FROM  " . Constantes::$ENTITY_PESSOA . " p "
                    . "WHERE "
                    . "p.nome LIKE ?1 ";

            $nomeAjustado = '%' . $nome . '%';
            $resultado = $this->getEntityManager()->createQuery($dql)
                    ->setParameter(1, $nomeAjustado)
                    ->getResult();
            return $resultado;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Localizar pessoa por CPF
     * 
     * @param String $CPF
     * @return Pessoa
     * @throws Exception
     */
    public function encontrarPorCPF($CPF) {
        $resposta = null;
        try {
            $pessoa = $this->getEntityManager()
                    ->getRepository($this->getEntity())
                    ->findOneBy(array(Constantes::$ENTITY_PESSOA_DOCUMENTO => $CPF));
            if ($pessoa) {
                $resposta = $pessoa;
            }
            return $resposta;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function verificarSeTemCPFCadastrado($CPF) {
        $resposta = false;
        try {
            $dql = "SELECT p.id, p.nome, p.documento, p.email, p.senha "
                    . "FROM  " . Constantes::$ENTITY_PESSOA . " p "
                    . "WHERE "
                    . "p.documento = ?1 ";

            $resultado = $this->getEntityManager()->createQuery($dql)
                    ->setParameter(1, $CPF)
                    ->getResult();
            if (count($resultado) > 0) {
                $resposta = true;
            }
            return $resposta;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Localizar pessoa por token
     * 
     * @param String $token
     * @return Pessoa
     * @throws Exception
     */
    public function encontrarPorToken($token) {
        try {
            $pessoa = $this->getEntityManager()
                    ->getRepository($this->getEntity())
                    ->findOneBy(array(Constantes::$ENTITY_PESSOA_TOKEN => $token));
            return $pessoa;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * Localizar pessoa por cpf e data de nascimento
     * 
     * @param int $cpfUltimosDigitos
     * @param String $dataNascimento
     * @return Pessoa
     * @throws Exception
     */
    public function encontrarPorCPFEDataNascimento($cpfUltimosDigitos, $dataNascimento) {

        $criteria = new Criteria();
        $criteria->andWhere(
                $criteria->expr()->eq(
                        Constantes::$ENTITY_PESSOA_DATA_NASCIMENTO, $dataNascimento
                )
        );
        $pessoas = $this->getEntityManager()
                ->getRepository($this->getEntity())
                ->matching($criteria);

        $pessoa = null;
        foreach ($pessoas as $p) {
            $cpfTratado = substr(str_pad($p->getDocumento(), 11, 0, STR_PAD_LEFT), 9, 2);
            if ($cpfTratado == $cpfUltimosDigitos) {
                $pessoa = $p;
            }
        }
        return $pessoa;
    }

    /**
     * Atualiza a aluno com dados da busca do cpf
     * 
     * @param Pessoa $pessoa
     * @param array $post_data
     * @param int $tipo
     * @return Pessoa $pessoa
     */
    public function atualizarAlunoComDadosDaBuscaPorCPF($pessoa, $post_data, $tipo = 0) {
        try {
            $pessoa->setDocumento(intval($post_data[Constantes::$FORM_CPF . $tipo]));
            $pessoa->setNome($post_data[Constantes::$FORM_NOME . $tipo]);
            $pessoa->setEmail($post_data[Constantes::$FORM_EMAIL . $tipo]);
            $pessoa->setData_nascimento(Funcoes::mudarPadraoData($post_data[Constantes::$FORM_DATA_NASCIMENTO . $tipo], 0));
            $this->persistir($pessoa, false);

            return $pessoa;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * Busca Pessoa Lider para o Revisao por Id  (Não retorna excesção caso não encontre)
     * @param idPessoa
     */
    public function encontrarPorIdPessoaParaRevisao($idPessoa) {
        $idInteiro = (int) $idPessoa;

        $entidade = $this->getEntityManager()->find($this->getEntity(), $idInteiro);
        if (!$entidade) {
            return false;
        }
        return $entidade;
    }

}
