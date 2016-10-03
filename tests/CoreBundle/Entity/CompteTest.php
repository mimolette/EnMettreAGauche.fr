<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\Compte;
use CoreBundle\Entity\CompteCheque;

class CompteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Compte
     */
    public function testVideCompte()
    {
        $compte = new CompteCheque();
        $this->assertNull($compte->getId());

        return $compte;
    }

    /**
     * @depends testVideCompte
     * @param Compte $compte
     */
    public function testToString(Compte $compte)
    {
        // si aucune valeur assigné au nom ou numéro
        $this->assertEquals('', $compte);

        // affectation d'un nom mais pas de numéro
        $compte->setNom('Livret A');
        $this->assertEquals('Livret A', $compte);

        // affectation d'un numéro en plus du nom
        $compte->setNumero('8896652035');
        $this->assertEquals('Livret A (8896652035)', $compte);

        // affectation uniquement d'un numéro, mais pas de nom
        $compte->setNom(null);
        $this->assertEquals('8896652035', $compte);
    }
}
