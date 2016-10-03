<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\Chequier;

class ChequierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Chequier
     */
    public function testVideChequier()
    {
        $chequier = new Chequier();
        $this->assertNull($chequier->getId());

        return $chequier;
    }

    /**
     * @depends testVideChequier
     * @param Chequier $chequier
     */
    public function testToString(Chequier $chequier)
    {
        // si aucune valeur assigné au nom ou numéro
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
