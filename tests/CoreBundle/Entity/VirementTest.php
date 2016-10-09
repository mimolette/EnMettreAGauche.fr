<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\Virement;

/**
 * VirementTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * VirementTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class VirementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Virement
     * @covers Virement::getId
     */
    public function testVideVirement()
    {
        // création d'une nouvelle opération courante
        $ope = new Virement;
        $this->assertNull($ope->getId());

        return $ope;
    }
}