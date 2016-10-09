<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\ModePaiement;

/**
 * ModePaiementTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * ModePaiementTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class ModePaiementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return ModePaiement
     * @covers ModePaiement::getId
     */
    public function testVideModePaiement()
    {
        // création d'un mode de paiement
        $mode = new ModePaiement();
        $this->assertNotNull($mode->getId());

        return $mode;
    }
}