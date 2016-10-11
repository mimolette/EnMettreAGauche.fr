<?php

namespace CoreBundle\Tests\Service;

use CoreBundle\Entity\AjustementSolde;
use CoreBundle\Entity\Compte;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Service\MiseAJourSolde;
use MasterBundle\Test\AbstractMasterService;

/**
 * MiseAJourSoldeTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * MiseAJourSoldeTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class MiseAJourSoldeTest extends AbstractMasterService
{
    /**
     * @return MiseAJourSolde
     */
    public function testVideService()
    {
        $this->setUp();

        return $this->get('emag.core.mise_a_jour_solde');
    }

    /**
     * @uses vérifie que le nouveau solde du compte corresponds bien au solde après
     * ajustement et vérification que l'ancien solde corresponds bien au solde avant
     * dans l'objet AjustementSolde
     * @param MiseAJourSolde $service
     * @depends testVideService
     * @covers MiseAJourSolde::parAjustement
     */
    public function testParAjustement(MiseAJourSolde $service)
    {
        // création d'un comtpe actif et d'un type de compte qui autorise les ajustements
        $compte = new Compte();
        $compte->setActive(true);
        $typeCompte = new TypeCompte();
        $typeCompte->setAutoriseAjustements(true);
        $compte->setType($typeCompte);
        // affectation d'un solde au compte
        $compte->setSolde(1456.23);

        // création d'un ajustement
        $ajustement = new AjustementSolde();
        $ajustement->setSoldeApres(1460.5);
        // affectation du comte
        $ajustement->setCompte($compte);

        // utilisation de la méthode
        $service->parAjustement($ajustement);

        // vérification que la nouveau solde du compte corresponds
        $this->assertEquals(1460.5, $compte->getSolde());
        // vérification que l'ajustement contient bien le solde avant
        $this->assertEquals(1456.23, $ajustement->getSoldeAvant());
    }
    
    /**
     * @return array
     */
    public function operationsProvider()
    {
        return [
            [
                15.36,
                [26.0, -25.5, 45.36, 152.18],
                213.4,
            ],
            [
                -95.2,
                [25.36, 1.5, -58.12, -99.99],
                -226.45,
            ],
            [
                1526.5,
                [14.28, -256.99, -1230.45, 98.56, 105.23, 100.0, -56.21],
                300.92,
            ],
        ];
    }
}
