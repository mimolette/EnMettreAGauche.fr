<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\Compte;

/**
 * CompteTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * CompteTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class CompteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Compte
     * @covers Compte::getId
     * @covers Compte::isActive
     */
    public function testVideCompte()
    {
        $compte = new Compte();
        $this->assertNull($compte->getId());
        $this->assertTrue($compte->isActive());

        return $compte;
    }

    /**
     * @uses vérifie si l'affichage d'un compte sous forme de chaine de caractères renvoi bien son nom
     *               et son numéro ou bien seulement l'un des deux
     * @depends testVideCompte
     * @param Compte $compte
     * @covers Compte::__toString
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

    /**
     * @uses vérifie si le solde du compte renvoi bien 0.0 lorsque celui-ci n'as pas été initialisé
     * @depends testVideCompte
     * @param Compte $compte
     * @covers Compte::getSolde
     */
    public function testGetSolde(Compte $compte)
    {
        // si aucune valeur assigné au solde
        $this->assertEquals(0.0, $compte->getSolde());
        $this->assertNotNull($compte->getSolde());

        // affectation d'un valeur de solde
        $compte->setSolde(45.56);
        $this->assertEquals(45.56, $compte->getSolde());
    }
}
