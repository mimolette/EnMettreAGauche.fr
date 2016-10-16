<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\OperationCourante;

/**
 * OperationCouranteTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * OperationCouranteTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class OperationCouranteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return OperationCourante
     * @covers OperationCourante::getId
     */
    public function testVideOperationCourante()
    {
        // création d'une nouvelle opération courante
        $ope = new OperationCourante();
        $this->assertNull($ope->getId());
        $this->assertFalse($ope->isComptabilise());

        return $ope;
    }
}