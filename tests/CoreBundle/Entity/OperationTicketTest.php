<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\OperationTicket;

/**
 * OperationTicketTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * OperationTicketTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class OperationTicketTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return OperationTicket
     */
    public function testVideOperationTicket()
    {
        $operation = new OperationTicket();
        $this->assertNull($operation->getId());
        $this->assertEquals(0, $operation->getNbTicket());

        return $operation;
    }

    /**
     * @depends testVideOperationTicket
     * @param OperationTicket $operation
     */
    public function testCalculMontant(OperationTicket $operation)
    {
        // affectation d'un nombre de ticket
        $operation->setNbTicket(8);

        // calcul du montant
        $operation->calculMontant(7.5);

        $this->assertEquals(60.0, $operation->getMontant());
    }
}
