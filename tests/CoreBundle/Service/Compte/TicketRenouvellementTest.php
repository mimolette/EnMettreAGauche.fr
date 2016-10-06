<?php

namespace CoreBundle\Tests\Service\Compte;

use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\Renouvellement;
use CoreBundle\Service\Compte\TicketRenouvellement;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

class TicketRenouvellementTest extends AbstractMasterService
{
    /**
     * @return TicketRenouvellement
     */
    public function testVideService()
    {
        $this->setUp();

        return $this->get('emag.core.compte.ticket_renouvellement');
    }

    /**
     * @depends testVideService
     * @param TicketRenouvellement $service
     */
    public function testFailRenouvellerCompteInactif(TicketRenouvellement $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création du compte
        $compte = new CompteTicket();
        $compte->setActive(false);

        // création d'un renouvellement
        $renouvellement = new Renouvellement();

        // test de la méthode de renouvellement du nombre de ticket
        $service->renouvellerCompte($compte, $renouvellement);
    }

    /**
     * @depends testVideService
     * @param TicketRenouvellement $service
     */
    public function testFailRenouvellerPasNbTicket(TicketRenouvellement $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::PAS_VALEUR_ATTENDUE);

        // création du compte
        $compte = new CompteTicket();

        // création d'un renouvellement
        $renouvellement = new Renouvellement();

        // test de la méthode de renouvellement du nombre de ticket
        $service->renouvellerCompte($compte, $renouvellement);
    }

    /**
     * @depends testVideService
     * @param TicketRenouvellement $service
     */
    public function testFailRenouvellerPasNbTicketValide(TicketRenouvellement $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::VALEURS_INCOHERENTES);

        // création du compte
        $compte = new CompteTicket();
        $compte->setNbTickets(4);

        // création d'un renouvellement
        $renouvellement = new Renouvellement();
        $renouvellement->setNbTickets(-2);

        // test de la méthode de renouvellement du nombre de ticket
        $service->renouvellerCompte($compte, $renouvellement);
    }

    /**
     * @depends testVideService
     * @param TicketRenouvellement $service
     */
    public function testRenouvellerCompte(TicketRenouvellement $service)
    {
        // création du compte
        $compte = new CompteTicket();
        $compte->setNbTickets(4);

        // création d'un renouvellement
        $renouvellement = new Renouvellement();
        $renouvellement->setNbTickets(21);

        // renouvellemnt du nombre de ticket
        $service->renouvellerCompte($compte, $renouvellement);

        // test si le nouveau solde de ticket correspond
        $this->assertEquals(25, $compte->getNbTickets());
    }
}
