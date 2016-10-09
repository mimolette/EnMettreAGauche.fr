<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\TransfertArgent;

/**
 * TransfertArgentTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * TransfertArgentTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class TransfertArgentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return TransfertArgent
     * @covers TransfertArgent::getId
     */
    public function testVideTransfertArgent()
    {
        // création d'un nouveau transfert d'argent
        $ope = new TransfertArgent;
        $this->assertNull($ope->getId());

        return $ope;
    }
}