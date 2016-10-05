<?php

namespace CoreBundle\Tests\Service\Compte;

use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\Renouvellement;
use CoreBundle\Service\Compte\TicketRenouvellement;
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
    public function testFailRenouvellerCompte(TicketRenouvellement $service)
    {
        $this->expectException(EmagException::class);
        
        // création d'un nouveau compte
        $compte = new CompteTicket();

        // création d'un renouvellement
        $renouvellement = new Renouvellement();
        
        $service->renouvellerCompte($compte, $renouvellement);
    }
}
