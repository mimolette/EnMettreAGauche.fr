<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\OperationEspeces;

/**
 * OperationEspecesTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * OperationEspecesTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class OperationEspecesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return OperationEspeces
     * @covers OperationEspeces::getId
     */
    public function testVideOperationEspeces()
    {
        // création d'une nouvelle opération d'espèces
        $ope = new OperationEspeces;
        $this->assertNull($ope->getId());

        return $ope;
    }
}