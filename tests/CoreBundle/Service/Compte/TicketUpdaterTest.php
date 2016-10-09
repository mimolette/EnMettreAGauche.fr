<?php

namespace CoreBundle\Tests\Service\Compte;

use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\OperationTicket;
use CoreBundle\Service\Compte\TicketUpdater;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;
use MasterBundle\Test\AbstractMasterService;

/**
 * TicketUpdaterTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * TicketUpdaterTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class TicketUpdaterTest extends AbstractMasterService
{
    /**
     * @return TicketUpdater
     */
    public function testVideService()
    {
        $this->setUp();

        return $this->get('emag.core.compte.ticket_updater');
    }

    /**
     * @depends testVideService
     * @param TicketUpdater $service
     */
    public function testFailUpdateNbTicketCompteInactif(TicketUpdater $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création du compte
        $compte = new CompteTicket();
        $compte->setActive(false);

        // création d'une opération
        $operation = new OperationTicket();

        // test de la méthode de mise à jour du nombre de ticket
        $service->updateNbTicket($compte, $operation);
    }

    /**
     * @param TicketUpdater $service
     * @depends testVideService
     */
    public function testFailUpdateNbTicket(
        TicketUpdater $service
    ) {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création d'un compte
        $compte = new CompteTicket();

        // création d'une opération
        $operation = new OperationTicket();

        // attribution d'un nombre de ticket au compte
        $compte->setNbTickets(3);

        // attribution du d'un nombre de ticket à l'opération
        $operation->setNbTicket(5);

        // test de la méthode de mise à jour du nombre de ticket
        $service->updateNbTicket($compte, $operation);
    }

    /**
     * @param TicketUpdater $service
     * @depends testVideService
     */
    public function testUpdateNbTicket(
        TicketUpdater $service
    ) {
        // création d'un compte
        $compte = new CompteTicket();

        // création d'une opération
        $operation = new OperationTicket();

        // attribution d'un nombre de ticket au compte
        $compte->setNbTickets(19);

        // attribution du d'un nombre de ticket à l'opération
        $operation->setNbTicket(3);

        // test de la méthode de mise à jour du nombre de ticket
        $service->updateNbTicket($compte, $operation);

        // test si le nombre de ticket du compte après est bien 16
        $this->assertEquals(16, $compte->getNbTickets());
    }

    /**
     * @param TicketUpdater $service
     * @depends testVideService
     */
    public function testUpdateNbTicketRelationfinale(
        TicketUpdater $service
    ) {
        // création d'un compte
        $compte = new CompteTicket();

        // création d'une opération
        $operation = new OperationTicket();

        // attribution d'un nombre de ticket au compte
        $compte->setNbTickets(19);

        // attribution du d'un nombre de ticket à l'opération
        $operation->setNbTicket(3);

        // test de la méthode de mise à jour du nombre de ticket
        $service->updateNbTicket($compte, $operation);

        // test si le possède bien $compte comme compte
        $this->assertEquals($compte, $operation->getCompte());

        // test si le compte possédent bien $operation parmi ses opérations
        $possedeOperation = $compte->getOperations()->contains($operation);
        $this->assertTrue($possedeOperation);
    }
}
