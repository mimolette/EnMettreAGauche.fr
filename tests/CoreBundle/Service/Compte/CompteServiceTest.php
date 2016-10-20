<?php

namespace CoreBundle\Tests\Service\Compte;

use CoreBundle\Entity\Compte;
use CoreBundle\Entity\OperationCourante;
use CoreBundle\Entity\TransfertArgent;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Service\Compte\CompteService;
use CoreBundle\Tests\Service\AbstractMasterService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * CompteServiceTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * CompteServiceTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class CompteServiceTest extends AbstractMasterService
{
    /**
     * @return CompteService
     */
    public function testVideService()
    {
        $this->setUp();

        return $this->get('emag.core.compte');
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
     * @uses vérifie que la méthode lève une exception dans le cas ou la mise à jour
     * du solde du compte débiteur de l'opération courante n'est pas possible car
     * celui-ci n'autorise pas les soldes négatif
     * @param CompteService $service
     * @depends testVideService
     * @covers CompteService::miseAjourSoldeParOperation
     */
    public function testFailMiseAjourSoldeParOperation1(CompteService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création d'un opération courante
        $operation = new OperationCourante();

        // récupération d'un compte vide et de son type de compte
        $compte = $this->testVideCompte();
        $typeCompte = $compte->getType();

        // affectation du compte à l'opération
        $operation->setCompte($compte);

        // le type de compte n'autorise pas les soldes négatif
        $typeCompte->setEtreNegatif(false);
        // solde du compte
        $compte->setSolde(45.23);
        // montant de l'opération
        $operation->setMontant(-89.65);

        // test de la méthode
        $service->miseAjourSoldeParOperation($operation);
    }

    /**
     * @uses vérifie que la méthode lève une exception dans le cas ou la mise à jour
     * du solde du compte débiteur de l'opération transfert d'argent n'est pas possible
     * car celui-ci n'autorise pas les soldes négatif
     * @param CompteService $service
     * @depends testVideService
     * @covers CompteService::miseAjourSoldeParOperation
     */
    public function testFailMiseAjourSoldeParOperation2(CompteService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création d'un opération transfert d'argent
        $operation = new TransfertArgent();

        // récupération d'un compte débiteur vide et de son type de compte
        $compteDebiteur = $this->testVideCompte();
        $typeCompte = $compteDebiteur->getType();

        // récupération d'un compte créditeur vide
        $compteCrediteur = $this->testVideCompte();

        // affectation des comptes à l'opération
        $operation->setCompte($compteDebiteur);
        $operation->setCompteCrediteur($compteCrediteur);

        // le type de compte débiteur n'autorise pas les soldes négatif
        $typeCompte->setEtreNegatif(false);
        // solde du compte
        $compteDebiteur->setSolde(25.2);
        // montant de l'opération (devrais toujours être positif)
        $operation->setMontant(25.3);

        // test de la méthode
        $service->miseAjourSoldeParOperation($operation);
    }

    /**
     * @uses vérifie que la méthode retourne l'opération de type transfert d'argent,
     * le compte débiteur et le compte créditeur dans un tableau, dans le cas ou
     * la mise à jour du solde des comptes est possible
     * @param CompteService $service
     * @depends testVideService
     * @covers CompteService::miseAjourSoldeParOperation
     */
    public function testMiseAjourSoldeParOperation1(CompteService $service)
    {
        // création d'un opération transfert d'argent
        $operation = new TransfertArgent();

        // récupération d'un compte débiteur vide et de son type de compte
        $compteDebiteur = $this->testVideCompte();
        $typeCompte = $compteDebiteur->getType();

        // récupération d'un compte créditeur vide
        $compteCrediteur = $this->testVideCompte();

        // affectation des comptes à l'opération
        $operation->setCompte($compteDebiteur);
        $operation->setCompteCrediteur($compteCrediteur);

        // le type de compte débiteur n'autorise pas les soldes négatifs
        $typeCompte->setEtreNegatif(false);
        // solde du compte
        $compteDebiteur->setSolde(45.89);
        // montant de l'opération
        $operation->setMontant(20.0);

        // test de la méthode
        $elements = $service->miseAjourSoldeParOperation($operation);

        // vérifie que l'opération est présente dans le tableau
        $this->assertTrue(in_array($operation, $elements));
        // vérifie que le compte débiteur est présent dans le tableau
        $this->assertTrue(in_array($compteDebiteur, $elements));
        // vérifie que le compte créditeur est présent dans le tableau
        $this->assertTrue(in_array($compteCrediteur, $elements));
        // vérifie que le tableau ne comporte que 3 valeurs
        $this->assertEquals(3, count($elements));
    }

    /**
     * @uses vérifie que la méthode retourne l'opération et le compte débiteur,
     * dans un tableau, dans le cas ou la mise à jour du solde du compte est possible
     * @param CompteService $service
     * @depends testVideService
     * @covers CompteService::miseAjourSoldeParOperation
     */
    public function testMiseAjourSoldeParOperation2(CompteService $service)
    {
        // création d'un opération transfert d'argent
        $operation = new OperationCourante();

        // récupération d'un compte vide et de son type de compte
        $compteDebiteur = $this->testVideCompte();
        $typeCompte = $compteDebiteur->getType();

        // affectation du comptes à l'opération
        $operation->setCompte($compteDebiteur);

        // le type de compte débiteur autorise les soldes négatifs
        $typeCompte->setEtreNegatif(true);
        // solde du compte
        $compteDebiteur->setSolde(789.65);
        // montant de l'opération
        $operation->setMontant(-255.5);

        // test de la méthode
        $elements = $service->miseAjourSoldeParOperation($operation);

        // vérifie que l'opération est présente dans le tableau
        $this->assertTrue(in_array($operation, $elements));
        // vérifie que le compte débiteur est présent dans le tableau
        $this->assertTrue(in_array($compteDebiteur, $elements));
        // vérifie que le tableau ne comporte que 2 valeurs
        $this->assertEquals(2, count($elements));
    }

    /**
     * @uses vérifie que la méthode met à jour les bonnes valeurs de soldes des comptes
     * débiteur et créditeur dans le cas ou la mise à jour est valide
     * Solde compte débiteur avant  = 582.36
     * Solde compte Créditeur avant = 120.0
     * Montant de l'opération       = 50.0
     * Solde compte débiteur après  = 532.36
     * Solde compte Créditeur après = 170.0
     * @param CompteService $service
     * @depends testVideService
     * @covers CompteService::miseAjourSoldeParOperation
     */
    public function testMiseAjourSoldeParOperation3(CompteService $service)
    {
        // création d'un opération transfert d'argent
        $operation = new TransfertArgent();

        // récupération d'un compte débiteur vide et de son type de compte
        $compteDebiteur = $this->testVideCompte();
        $typeCompte = $compteDebiteur->getType();

        // récupération d'un compte créditeur vide
        $compteCrediteur = $this->testVideCompte();

        // affectation des comptes à l'opération
        $operation->setCompte($compteDebiteur);
        $operation->setCompteCrediteur($compteCrediteur);

        // le type de compte débiteur n'autorise pas les soldes négatifs
        $typeCompte->setEtreNegatif(false);
        // solde du compte débiteur
        $compteDebiteur->setSolde(582.36);
        // solde du compte créditeur
        $compteCrediteur->setSolde(120.0);
        // montant de l'opération
        $operation->setMontant(50.0);

        // test de la méthode
        $service->miseAjourSoldeParOperation($operation);

        // vérifie que le solde du compte débiteur est correct
        $this->assertEquals(532.36, $compteDebiteur->getSolde());
        // vérifie que le solde du compte créditeur est correct
        $this->assertEquals(170.0, $compteCrediteur->getSolde());
    }

    /**
     * @uses vérifie que la méthode met à jour les bonnes valeurs de soldes des comptes
     * débiteur et créditeur dans le cas ou la mise à jour est valide
     * Solde compte débiteur avant  = 125.5
     * Solde compte Créditeur avant = 855.69
     * Montant de l'opération       = 222.22
     * Solde compte débiteur après  = -96.72
     * Solde compte Créditeur après = 1077.91
     * @param CompteService $service
     * @depends testVideService
     * @covers CompteService::miseAjourSoldeParOperation
     */
    public function testMiseAjourSoldeParOperation4(CompteService $service)
    {
        // création d'un opération transfert d'argent
        $operation = new TransfertArgent();

        // récupération d'un compte débiteur vide et de son type de compte
        $compteDebiteur = $this->testVideCompte();
        $typeCompte = $compteDebiteur->getType();

        // récupération d'un compte créditeur vide
        $compteCrediteur = $this->testVideCompte();

        // affectation des comptes à l'opération
        $operation->setCompte($compteDebiteur);
        $operation->setCompteCrediteur($compteCrediteur);

        // le type de compte débiteur autorise les soldes négatifs
        $typeCompte->setEtreNegatif(true);
        // solde du compte débiteur
        $compteDebiteur->setSolde(125.5);
        // solde du compte créditeur
        $compteCrediteur->setSolde(855.69);
        // montant de l'opération
        $operation->setMontant(222.22);

        // test de la méthode
        $service->miseAjourSoldeParOperation($operation);

        // vérifie que le solde du compte débiteur est correct
        $this->assertEquals(-96.72, $compteDebiteur->getSolde());
        // vérifie que le solde du compte créditeur est correct
        $this->assertEquals(1077.91, $compteCrediteur->getSolde());
    }

    /**
     * @uses vérifie que la méthode met à jour la bonne valeur de soldes du compte
     * débiteur dans le cas ou la mise à jour est valide
     * Solde compte débiteur avant  = 258.9
     * Montant de l'opération       = -158.9
     * Solde compte débiteur après  = 100.0
     * @param CompteService $service
     * @depends testVideService
     * @covers CompteService::miseAjourSoldeParOperation
     */
    public function testMiseAjourSoldeParOperation5(CompteService $service)
    {
        // création d'un opération courante
        $operation = new OperationCourante();

        // récupération d'un compte vide et de son type de compte
        $compte = $this->testVideCompte();
        $typeCompte = $compte->getType();

        // affectation du compte à l'opération
        $operation->setCompte($compte);

        // le type de compte autorise les soldes négatifs
        $typeCompte->setEtreNegatif(true);
        // solde du compte
        $compte->setSolde(258.9);
        // montant de l'opération
        $operation->setMontant(-158.9);

        // test de la méthode
        $service->miseAjourSoldeParOperation($operation);

        // vérifie que le solde du compte est correct
        $this->assertEquals(100.0, $compte->getSolde());
    }

    /**
     * @uses vérifie que la méthode met à jour la bonne valeur de soldes du compte
     * débiteur dans le cas ou la mise à jour est valide
     * Solde compte débiteur avant  = 58.96
     * Montant de l'opération       = -58.96
     * Solde compte débiteur après  = 0.0
     * @param CompteService $service
     * @depends testVideService
     * @covers CompteService::miseAjourSoldeParOperation
     */
    public function testMiseAjourSoldeParOperation6(CompteService $service)
    {
        // création d'un opération courante
        $operation = new OperationCourante();

        // récupération d'un compte vide et de son type de compte
        $compte = $this->testVideCompte();
        $typeCompte = $compte->getType();

        // affectation du compte à l'opération
        $operation->setCompte($compte);

        // le type de compte n'autorise pas les soldes négatifs
        $typeCompte->setEtreNegatif(false);
        // solde du compte
        $compte->setSolde(58.96);
        // montant de l'opération
        $operation->setMontant(-58.96);

        // test de la méthode
        $service->miseAjourSoldeParOperation($operation);

        // vérifie que le solde du compte est correct
        $this->assertEquals(.0, $compte->getSolde());
    }

    /**
     * @uses vérifie que la méthode retourne vrai dans le cas ou le montant du nouveau
     * solde est positif et si le type de compte n'autorise pas les soldes négatifs
     * @param CompteService $service
     * @depends testVideService
     * @covers CompteService::isMajSoldePossible
     */
    public function testIsMajSoldePossible1(CompteService $service)
    {
        // récupération d'un compte vide et de son type de compte
        $compte = $this->testVideCompte();
        $typeCompte = $compte->getType();

        // n'autorise pas les soldes négatif
        $typeCompte->setEtreNegatif(false);

        // test de la méthode
        $this->assertTrue($service->isMajSoldePossible(14.52, $compte));
    }

    /**
     * @uses vérifie que la méthode retourne vrai dans le cas ou le montant du nouveau
     * solde est positif et si le type de compte autorise les soldes négatifs
     * @param CompteService $service
     * @depends testVideService
     * @covers CompteService::isMajSoldePossible
     */
    public function testIsMajSoldePossible2(CompteService $service)
    {
        // récupération d'un compte vide et de son type de compte
        $compte = $this->testVideCompte();
        $typeCompte = $compte->getType();

        //aautorise les soldes négatif
        $typeCompte->setEtreNegatif(true);

        // test de la méthode
        $this->assertTrue($service->isMajSoldePossible(1458.63, $compte));
    }

    /**
     * @uses vérifie que la méthode retourne vrai dans le cas ou le montant du nouveau
     * solde est négatif et si le type de compte autorise les soldes négatifs
     * @param CompteService $service
     * @depends testVideService
     * @covers CompteService::isMajSoldePossible
     */
    public function testIsMajSoldePossible3(CompteService $service)
    {
        // récupération d'un compte vide et de son type de compte
        $compte = $this->testVideCompte();
        $typeCompte = $compte->getType();

        //aautorise les soldes négatif
        $typeCompte->setEtreNegatif(true);

        // test de la méthode
        $this->assertTrue($service->isMajSoldePossible(-123.5, $compte));
    }

    /**
     * @uses vérifie que la méthode retourne faux dans le cas ou le montant du nouveau
     * solde est négatif et si le type de compte n'autorise pas les soldes négatifs
     * @param CompteService $service
     * @depends testVideService
     * @covers CompteService::isMajSoldePossible
     */
    public function testIsMajSoldePossible4(CompteService $service)
    {
        // récupération d'un compte vide et de son type de compte
        $compte = $this->testVideCompte();
        $typeCompte = $compte->getType();

        // n'autorise pas les soldes négatif
        $typeCompte->setEtreNegatif(false);

        // test de la méthode
        $this->assertFalse($service->isMajSoldePossible(-99.65, $compte));
    }
}
