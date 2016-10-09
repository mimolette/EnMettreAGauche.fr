<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\Prelevement;

/**
 * PrelevementTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * PrelevementTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class PrelevementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Prelevement
     * @covers Prelevement::getId
     */
    public function testVidePrelevement()
    {
        // création d'un nouveau prélèvement
        $prelevement = new Prelevement;
        $this->assertNull($prelevement->getId());

        return $prelevement;
    }
}