<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\AjustementSolde;

/**
 * AjustementSoldeTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * AjustementSoldeTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class AjustementSoldeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return AjustementSolde
     * @covers AjustementSolde::getId
     * @covers AjustementSolde::getDate
     */
    public function testVideAjustementSolde()
    {
        $ajustement = new AjustementSolde();
        $this->assertNull($ajustement->getId());

        // test de la date à la précision du jour
        $dateJour = new \DateTime();
        $dateJourText = $dateJour->format('d-m-Y');
        $dateAjustement = $ajustement->getDate()->format('d-m-Y');
        $this->assertEquals($dateJourText, $dateAjustement);

        return $ajustement;
    }

    /**
     * @depends testVideAjustementSolde
     * @param AjustementSolde $ajustement
     */
    public function testGetSoldeAvant(AjustementSolde $ajustement)
    {
        // si aucune valeur assigné au solde avant
        $this->assertEquals(0.0, $ajustement->getSoldeAvant());

        // affectation d'un valeur de solde
        $ajustement->setSoldeAvant(65.20);
        $this->assertEquals(65.20, $ajustement->getSoldeAvant());
    }
}
