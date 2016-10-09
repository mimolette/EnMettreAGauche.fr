<?php

namespace CoreBundle\Tests\Service\Compte;

use CoreBundle\Entity\AjustementSolde;
use CoreBundle\Entity\Compte;
use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\OperationCourante;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Enum\TypeCompteEnum;
use CoreBundle\Service\Compte\SoldeUpdater;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;
use MasterBundle\Test\AbstractMasterService;

/**
 * SoldeUpdaterTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * SoldeUpdaterTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
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
     * @uses verifie si le service retourne une exception si le compte est inactif pour un ajustement
     * @covers SoldeUpdater::updateSoldeWithAjustement
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
        $ajustement->setCompte($compte);

        // test de la méthode d'ajustement du solde du compte
        $service->updateSoldeWithAjustement($ajustement);
    }

    /**
     * @uses vérifie si le service retourne une exception si le compte est inactif pour une opération
     * @depends testVideService
     * @covers SoldeUpdater::updateSoldeWithOperation
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
        $operation->setCompte($compte);

        // test de la méthode de mise a jour du solde du compte suite à une opération
        $service->updateSoldeWithOperation($operation);
    }

    /**
     * @uses vérifie si le service retourne une exception si l'ajustement n'est lié a acun compte
     * @depends testVideService
     * @param SoldeUpdater $service
     * @covers SoldeUpdater::updateSoldeWithAjustement
     */
    public function testFailUpdateAjustementPasLieCompte(SoldeUpdater $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création d'un ajustement
        $ajustement = new AjustementSolde();

        // test de la méthode d'ajustement de solde d'un compte
        $service->updateSoldeWithAjustement($ajustement);
    }

    /**
     * @uses vérifie si le service retourne une exception si l'opration n'est lié à aucun compte
     * @depends testVideService
     * @covers SoldeUpdater::updateSoldeWithOperation
     * @param SoldeUpdater $service
     */
    public function testFailUpdateOperationPasLieCompte(SoldeUpdater $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création d'un ajustement
        $operation = new OperationCourante();

        // test de la méthode de mise à jour d'une solde de compte
        $service->updateSoldeWithOperation($operation);
    }

    /**
     * @uses vérifie si le service retourne une exception si le compte n'autorise pas les ajustements
     * @depends testVideService
     * @covers SoldeUpdater::updateSoldeWithAjustement
     * @param SoldeUpdater $service
     */
    public function testFailAjustementPasAutorise(SoldeUpdater $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création d'un type de compte
        $typeCompte = new TypeCompte();
        $typeCompte->setNumeroUnique(TypeCompteEnum::TICKET_CHEQUE);

        // création du compte
        $compte = new CompteTicket();
        $compte->setType($typeCompte);

        // création d'un ajustement
        $ajustement = new AjustementSolde();
        $ajustement->setCompte($compte);

        // test de la méthode d'ajustement de solde d'un compte
        $service->updateSoldeWithAjustement($ajustement);
    }

    /**
     * @uses vérifie si le service retourne une exception dans le cas ou le solde du compte n'est pas égale
     *               au solde avant l'ajustement
     * @depends testVideService
     * @covers SoldeUpdater::updateSoldeWithAjustement
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
        $ajustement->setSoldeAvant(463.50);
        $ajustement->setSoldeApres(475.00);

        // test de la méthode de mise a jour d'un solde par ajustement
        $service->updateSoldeWithAjustement($ajustement);
    }

    /**
     * @uses vérifie si le service retourne une exception dans le cas ou l'ajustement ne possèdent pas un solde
     *               après valide.
     * @depends testVideService
     * @covers SoldeUpdater::updateSoldeWithAjustement
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
        $service->updateSoldeWithAjustement($ajustement);
    }

    /**
     * @uses vérifie si le service retourne une exception si l'ajustement provoque un solde
     *               négatif alors que le type de compte ne l'autorise pas.
     * @depends testVideService
     * @covers SoldeUpdater::updateSoldeWithAjustement
     * @param SoldeUpdater $service
     */
    public function testFailUpdateSoldeWithAjustementNegatif(SoldeUpdater $service)
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
        $service->updateSoldeWithAjustement($ajustement);
    }

    /**
     * @uses vérifie si les ajutements effectué sur un compte modifie bien la valeur du solde
     *               de ce compte.
     * @depends testVideService
     * @covers SoldeUpdater::updateSoldeWithAjustement
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
        $service->updateSoldeWithAjustement($ajustement);

        $this->assertEquals(15.95, $compte->getSolde());
    }

    /**
     * @uses vérifie si le service retourne une exception si l'opération provoque la mise
     *               en négatif du solde d'un comtpe qui ne l'autorise pas.
     * @depends testVideService
     * @covers SoldeUpdater::updateSoldeWithOperation
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
        $service->updateSoldeWithOperation($operation);
    }

    /**
     * @uses vérifie sur un ensemble d'opération effectué sur un compte modifie bien le solde
     *               de celui-ci de manière cohérente.
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
            $service->updateSoldeWithOperation($operation);
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
