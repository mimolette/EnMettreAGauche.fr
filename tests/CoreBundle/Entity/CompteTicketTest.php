<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\CompteTicket;

class CompteTicketTicketTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return CompteTicket
     */
    public function testVideCompteTicket()
    {
        $compte = new CompteTicket();
        $this->assertEquals(0, $compte->getNbTickets());

        return $compte;
    }
}
