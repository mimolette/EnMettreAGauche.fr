<?php

namespace CoreBundle\Tests\Service;

use CoreBundle\Entity\AjustementSolde;
use CoreBundle\Entity\Compte;
use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\Renouvellement;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Service\MiseAJourSolde;

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
     * @return Compte
     */
    public function testVideCompte()
    {
        // création d'un compte
        $compte = new Compte();
        // création d'un type de compte
        $typeCompte = new TypeCompte();

        // affectation du type au compte
        $compte->setType($typeCompte);

        return $compte;
    }

    /**
     * @uses vérifie que la méthode retourne l'ajustement et le compte dans un
     * tableau, dans le cas ou l'ajustement est possible
     * @param MiseAJourSolde $service
     * @depends testVideService
     * @covers MiseAJourSolde::parAjustement
     */
    public function testParAjustement1(MiseAJourSolde $service)
    {
        // création d'un ajustement
        $ajustement = new AjustementSolde();

        // récupération d'un compte vide et de son type de compte
        $compte = $this->testVideCompte();
        $typeCompte = $compte->getType();

        // affectation du compte à l'ajustement
        $ajustement->setCompte($compte);

        // le type de compte autorise les ajustements
        $typeCompte->setAutoriseAjustements(true);
        // solde du compte
        $compte->setSolde(15.26);
        // solde après l'ajustement
        $ajustement->setSoldeApres(15.35);

        // test de la méthode
        $elements = $service->parAjustement($ajustement);

        // vérifie que l'ajustement est présent dans le tableau
        $this->assertTrue(in_array($ajustement, $elements));
        // vérifie que le compte est présent dans le tableau
        $this->assertTrue(in_array($compte, $elements));
        // vérifie que le tableau ne comporte que 2 valeurs
        $this->assertEquals(2, count($elements));
    }

    /**
     * @uses vérifie que le nouveau solde du compte corresponds bien au solde après
     * ajustement et vérification que l'ancien solde corresponds bien au solde avant
     * dans l'objet AjustementSolde
     * @param MiseAJourSolde $service
     * @depends testVideService
     * @covers MiseAJourSolde::parAjustement
     */
    public function testParAjustement2(MiseAJourSolde $service)
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

        // vérification que le nouveau solde du compte corresponds
        $this->assertEquals(1460.5, $compte->getSolde());
        // vérification que l'ajustement contient bien le solde avant
        $this->assertEquals(1456.23, $ajustement->getSoldeAvant());
    }

    /**
     * @uses vérifie que la méthode retourne le renouvellement et le compte ticket
     * dans un tableau, dans le cas ou le renouvellement est possible
     * @param MiseAJourSolde $service
     * @depends testVideService
     * @covers MiseAJourSolde::parRenouvellement
     */
    public function testParRenouvellement1(MiseAJourSolde $service)
    {
        // création d'un renouvellement
        $renouvellement = new Renouvellement();

        // récupération d'un compte ticket vide et de son type de compte
        $compte = new CompteTicket();
        $typeCompte = new TypeCompte();
        $compte->setType($typeCompte);

        // affectation du compte au renouvellement
        $renouvellement->setCompte($compte);

        // solde de ticket du compte
        $compte->setNbTickets(2);
        // nombre de ticket du renouvellement
        $renouvellement->setNbTickets(9);

        // test de la méthode
        $elements = $service->parRenouvellement($renouvellement);

        // vérifie que le renouvellement est présent dans le tableau
        $this->assertTrue(in_array($renouvellement, $elements));
        // vérifie que le compte est présent dans le tableau
        $this->assertTrue(in_array($compte, $elements));
        // vérifie que le tableau ne comporte que 2 valeurs
        $this->assertEquals(2, count($elements));
    }

    /**
     * @uses vérifie que le nouveau nombre de ticket du compte est valide, ainsi que son
     * nouveau solde
     * @param MiseAJourSolde $service
     * @depends testVideService
     * @covers MiseAJourSolde::parAjustement
     */
    public function testParRenouvellement2(MiseAJourSolde $service)
    {
        // création d'un comtpe ticket actif
        $compte = new CompteTicket();
        $compte->setActive(true);
        // affectation d'un nombre de ticket au compte et d'un montant
        $compte->setNbTickets(5);
        $compte->setMontantTicket(7.5);

        // création d'un renouvellement
        $renouvellement = new Renouvellement();
        $renouvellement->setNbTickets(17);
        // affectation du comte
        $renouvellement->setCompte($compte);

        // utilisation de la méthode
        $service->parRenouvellement($renouvellement);

        // vérification que le nouveau nombre de ticket corresponds
        $this->assertEquals(22, $compte->getNbTickets());
        // vérification que le nouveau solde est valide
        $this->assertEquals(165.0, $compte->getSolde());
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
