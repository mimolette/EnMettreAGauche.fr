<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\Renouvellement;

/**
 * RenouvellementTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * RenouvellementTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class RenouvellementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Renouvellement
     * @covers Renouvellement::getId
     */
    public function testVideRenouvellement()
    {
        // création d'un nouveau renouvellement
        $renouvellement = new Renouvellement;
        $this->assertNull($renouvellement->getId());

        return $renouvellement;
    }
}