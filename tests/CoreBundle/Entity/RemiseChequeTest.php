<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\RemiseCheque;

/**
 * RemiseChequeTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * RemiseChequeTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class RemiseChequeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return RemiseCheque
     * @covers RemiseCheque::getId
     */
    public function testVideRemiseCheque()
    {
        // création d'une nouvelle remise de chèque
        $remiseCheque = new RemiseCheque;
        $this->assertNull($remiseCheque->getId());

        return $remiseCheque;
    }
}