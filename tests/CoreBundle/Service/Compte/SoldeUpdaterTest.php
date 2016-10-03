<?php

namespace CoreBundle\Tests\Service\Compte;

use CoreBundle\Entity\AjustementSolde;
use CoreBundle\Entity\CompteCheque;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Service\Compte\SoldeUpdater;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

class SoldeUpdaterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return SoldeUpdater
     */
    public function testVideService()
    {
        $service = new SoldeUpdater();

        return $service;
    }

    /**
     * @depends testVideService
     * @param SoldeUpdater $service
     */
    public function testFailUpdateSoldeWithAjustementMauvaisSoldeAvant(SoldeUpdater $service)
    {
        // On s'attends à la levé d'un expection avec le code d'erreur correspondant
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::VALEURS_INCOHERENTES);

        // création d'un compte
        $compte = new CompteCheque();
        // affectation d'un type de compte avec possibilité d'être négatif
        $typeCompteNegatif =  new TypeCompte();
        $typeCompteNegatif->setEtreNegatif(true);

        $compte->setType($typeCompteNegatif);
        $compte->setSolde(452.30);

        // création d'un ajustement de solde
        $ajustement = new AjustementSolde();
        $service->updateSoldeWithAjustement($compte, $ajustement);
    }

    /**
     * @depends testVideService
     * @param SoldeUpdater $service
     */
    public function testFailUpdateSoldeWithAjustementMauvaisSoldeAprès(SoldeUpdater $service)
    {
        // On s'attends à la levé d'un expection avec le code d'erreur correspondant
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::PAS_VALEUR_ATTENDUE);

        // création d'un compte
        $compte = new CompteCheque();
        // affectation d'un type de compte avec possibilité d'être négatif
        $typeCompteNegatif =  new TypeCompte();
        $typeCompteNegatif->setEtreNegatif(true);

        $compte->setType($typeCompteNegatif);
        $compte->setSolde(452.30);

        // création d'un ajustement de solde
        $ajustement = new AjustementSolde();
        $ajustement->setSoldeAvant(452.30);
        $service->updateSoldeWithAjustement($compte, $ajustement);
    }

    /**
     * @depends testVideService
     * @param SoldeUpdater $service
     */
    public function testFailUpdateSoldeWithAjustementTypeCompte(SoldeUpdater $service)
    {
        // On s'attends à la levé d'un expection avec le code d'erreur correspondant
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::VALEURS_INCOHERENTES);

        // création d'un compte
        $compte = new CompteCheque();
        // affectation d'un type de compte sans possibilité d'être négatif
        $typeCompte =  new TypeCompte();
        $typeCompte->setEtreNegatif(false);

        $compte->setType($typeCompte);
        $compte->setSolde(15.20);

        // création d'un ajustement de solde
        $ajustement = new AjustementSolde();
        $ajustement->setSoldeAvant(15.20);
        $ajustement->setSoldeApres(-5.26);
        $service->updateSoldeWithAjustement($compte, $ajustement);
    }

    /**
     * @depends testVideService
     * @param SoldeUpdater $service
     */
    public function testUpdateSoldeWithAjustement(SoldeUpdater $service)
    {
        // création d'un compte
        $compte = new CompteCheque();
        // affectation d'un type de compte avec possibilité d'être négatif
        $typeCompte =  new TypeCompte();
        $typeCompte->setEtreNegatif(true);

        $compte->setType($typeCompte);
        $compte->setSolde(15.20);

        // création d'un ajustement de solde
        $ajustement = new AjustementSolde();
        $ajustement->setSoldeAvant(15.20);
        $ajustement->setSoldeApres(15.95);
        $service->updateSoldeWithAjustement($compte, $ajustement);

        $this->assertEquals(15.95, $compte->getSolde());
    }
}
