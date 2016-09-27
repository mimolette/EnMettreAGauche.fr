<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\Couleur;

class CouleurTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Couleur
     */
    public function testEmptyCouleur()
    {
        $couleur = new Couleur();
        $this->assertNull($couleur->getId());
        $this->assertNull($couleur->getCodeHexa());

        return $couleur;
    }

    /**
     * @depends testEmptyCouleur
     * @param Couleur $couleur
     */
    public function testSetCodeHexa(Couleur $couleur)
    {
        $couleur->setCodeHexa("#454545");

        // assert
        $this->assertEquals("#454545", $couleur->getCodeHexa());

        $couleur2 = new Couleur();
        $couleur->setCodeHexa(454545);

        // assert
        $this->assertEquals("#454545", $couleur->getCodeHexa());
    }
}
