<?php

namespace Application\Controller\Factory;

use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\Validator\Exception\ExtensionNotLoadedException;

/**
 * Nome: CircuitoControllerFactory.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe para inicializar o controle
 */
class CircuitoControllerFactory {

    public function createServiceORM($sm) {
        // Serviço de Manipulação de entidade Doctrine    
        try {
            $doctrineORMEntityManager = $sm->get('Doctrine\ORM\EntityManager');
        } catch (ServiceNotCreatedException $e) {
            $doctrineORMEntityManager = null;
        } catch (ExtensionNotLoadedException $e) {
            $doctrineORMEntityManager = null;
        }

        return $doctrineORMEntityManager;
    }

}
