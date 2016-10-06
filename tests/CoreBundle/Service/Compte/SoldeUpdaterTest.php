<?php

namespace CoreBundle\Tests\Service\Compte;

use CoreBundle\Entity\AjustementSolde;
use CoreBundle\Entity\Compte;
use CoreBundle\Entity\OperationCourante;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Service\Compte\SoldeUpdater;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

class SoldeUpdaterTest extends AbstractMasterService
{
    /**
     * @return SoldeUpdater
     */
    public function testVideService()
    {
        $this->setUp();

        return $this->get('emag.core.compte.solde_updater');
    }

    /**
     * @depends testVideService
     * @param SoldeUpdater $service
     */
    public function testFailUpdateSoldeWithAjustementCompteInactif(SoldeUpdater $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création du compte
        $compte = new Compte();
        $compte->setActive(false);

        // création d'un ajustement
        $ajustement = new AjustementSolde();

        // test de la méthode d'ajustement du solde du compte
        $service->updateSoldeWithAjustement($compte, $ajustement);
    }

    /**
     * @depends testVideService
     * @param SoldeUpdater $service
     */
    public function testFailUpdateSoldeWithOperationCompteInactif(SoldeUpdater $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création du compte
        $compte = new Compte();
        $compte->setActive(false);

        // création d'un operation
        $operation = new OperationCourante();

        // test de la méthode de mise a jour du solde du compte suite à une opération
        $service->updateSoldeWithOperation($compte, $operation);
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
        $compte = new Compte();
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
        $compte = new Compte();
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
        $compte = new Compte();
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
        $compte = new Compte();
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

    /**
     * @depends testVideService
     * @param SoldeUpdater $service
     */
    public function testFailUpdateSoldeWithOperationTypeCompte(SoldeUpdater $service)
    {
        // On s'attends à la levé d'un expection avec le code d'erreur correspondant
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::VALEURS_INCOHERENTES);

        // création d'un compte
        $compte = new Compte();
        // affectation d'un type de compte sans possibilité d'être négatif
        $typeCompte =  new TypeCompte();
        $typeCompte->setEtreNegatif(false);

        $compte->setType($typeCompte);
        $compte->setSolde(256.23);

        // création d'une nouvelle opération
        $operation = new OperationCourante();
        $operation->setMontant(-325.50);
        $service->updateSoldeWithOperation($compte, $operation);
    }

    /**
     * @depends testVideService
     * @dataProvider  operationsProvider
     * @param float        $soldeInitial
     * @param array        $montants
     * @param float        $valeurAttendue
     * @param SoldeUpdater $service
     */
    public function testUpdateSoldeWithOperation(
        $soldeInitial,
        $montants,
        $valeurAttendue,
        SoldeUpdater $service
    ) {
        // création d'un compte
        $compte = new Compte();
        // affectation d'un type de compte
        $typeCompte =  new TypeCompte();
        $typeCompte->setEtreNegatif(true);

        $compte->setType($typeCompte);
        $compte->setSolde($soldeInitial);

        //parcourt des différents montants d'opération
        foreach ($montants as $montant) {
            // création d'une nouvelle opération
            $operation = new OperationCourante();
            $operation->setMontant($montant);
            $service->updateSoldeWithOperation($compte, $operation);
        }

        $this->assertEquals($valeurAttendue, $compte->getSolde());
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
