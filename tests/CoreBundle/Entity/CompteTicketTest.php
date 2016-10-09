<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\CompteTicket;

/**
 * CompteTicketTicketTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * CompteTicketTicketTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class CompteTicketTicketTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return CompteTicket
     * @covers CompteTicket::getId
     */
    public function testVideCompteTicket()
    {
        $compte = new CompteTicket();
        $this->assertNull($compte->getId());

        return $compte;
    }

    /**
     * @uses vérifie si le compte possedent toujours 0 ticket si cette valeur n'as pas été initialisé
     * @param CompteTicket $compte
     * @depends testVideCompteTicket
     * @covers CompteTicket::getNbTickets
     */
    public function testGetNbTickets(CompteTicket $compte)
    {
        $this->assertNotNull($compte->getNbTickets());
        $this->assertEquals(0, $compte->getNbTickets());

        // affectation d'un nombre de ticket
        $compte->setNbTickets(9);

        $this->assertEquals(9, $compte->getNbTickets());
    }
}
