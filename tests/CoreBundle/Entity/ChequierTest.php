<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\Chequier;

/**
 * ChequierTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * ChequierTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class ChequierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Chequier
     * @covers Chequier::isActive
     * @covers Chequier::getId
     */
    public function testVideChequier()
    {
        $chequier = new Chequier();
        $this->assertNull($chequier->getId());
        $this->assertTrue($chequier->isActive());

        return $chequier;
    }

    /**
     * @uses vérifie si l'affichage d'un chequier sous forme de chaine de caractère renvoi son nom
     *               et son numéro, ou l'un des deux.
     * @depends testVideChequier
     * @param Chequier $chequier
     * @covers Chequier::__toString
     */
    public function testToString(Chequier $chequier)
    {
        // si aucune valeur assignée au nom ou numéro
        $this->assertEquals('', $chequier);

        // affectation d'un nom mais pas de numéro
        $chequier->setNom('Chequier de Guillaume');
        $this->assertEquals('Chequier de Guillaume', $chequier);

        // affectation d'un numéro en plus du nom
        $chequier->setNumero('555888663200-65');
        $this->assertEquals('Chequier de Guillaume (555888663200-65)', $chequier);

        // affectation uniquement d'un numéro, mais pas de nom
        $chequier->setNom(null);
        $this->assertEquals('555888663200-65', $chequier);
    }
}
