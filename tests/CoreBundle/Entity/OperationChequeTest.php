<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\OperationCheque;

/**
 * OperationChequeTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * OperationChequeTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class OperationChequeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return OperationCheque
     * @covers OperationCheque::getId
     */
    public function testVideOperationCheque()
    {
        // création d'une nouvelle opération courante
        $ope = new OperationCheque;
        $this->assertNull($ope->getId());

        return $ope;
    }
}