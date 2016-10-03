<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\CompteCheque;
use CoreBundle\Entity\CompteSolde;

class CompteSoldeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return CompteSolde
     */
    public function testVideCompteSolde()
    {
        $compte = new CompteCheque();
        $this->assertNull($compte->getId());

        return $compte;
    }

    /**
     * @depends testVideCompteSolde
     * @param CompteSolde $compte
     */
    public function testGetSolde(CompteSolde $compte)
    {
        // si aucune valeur assigné au solde
        $this->assertEquals(0.0, $compte->getSolde());

        // affectation d'un valeur de solde
        $compte->setSolde(45.56);
        $this->assertEquals(45.56, $compte->getSolde());
    }
}
