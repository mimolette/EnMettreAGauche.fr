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
     * @covers OperationTicket::getId
     * @return OperationTicket
     */
    public function testVideOperationTicket()
    {
        $operation = new OperationTicket();
        $this->assertNull($operation->getId());

        return $operation;
    }
}
