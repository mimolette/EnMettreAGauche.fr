<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\Couleur;

/**
 * CouleurTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * CouleurTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class CouleurTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Couleur
     * @covers Couleur::getId
     * @covers Couleur::getCodeHexa
     */
    public function testEmptyCouleur()
    {
        $couleur = new Couleur();
        $this->assertNull($couleur->getId());
        $this->assertNull($couleur->getCodeHexa());

        return $couleur;
    }

    /**
     * @uses vérifie si l'affectation d'une couleur fonctionnement comme attendu
     * @depends testEmptyCouleur
     * @param Couleur $couleur
     * @covers Couleur::setCodeHexa
     */
    public function testSetCodeHexa(Couleur $couleur)
    {
        $couleur->setCodeHexa("#454545");

        // assert
        $this->assertEquals("#454545", $couleur->getCodeHexa());

        $couleur2 = new Couleur();
        $couleur->setCodeHexa(454545);

        // assert
        $this->assertNotEquals("#454545", $couleur->getCodeHexa());
    }
}
