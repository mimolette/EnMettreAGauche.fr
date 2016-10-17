<?php

namespace CoreBundle\Tests\Service\Operation;

use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\ModePaiement;
use CoreBundle\Entity\OperationTicket;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Service\Operation\OperationTicketService;
use CoreBundle\Tests\Service\AbstractMasterService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * OperationTicketServiceTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * OperationTicketServiceTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class OperationTicketServiceTest extends AbstractMasterService
{
    /**
     * @return OperationTicketService
     */
    public function testVideService()
    {
        $this->setUp();

        return $this->get('emag.core.operation.ticket');
    }

    /**
     * @uses retourne une opération de ticket lié à un compte, type de compte,
     * mode de paiement
     * @return OperationTicket
     */
    public function testVideOperationTicket()
    {
        // création d'un type de compte
        $typeCompte = new TypeCompte();
        // création de mode de paiements
        $modePaiement1 = new ModePaiement();
        $modePaiement2 = new ModePaiement();
        $modePaiement3 = new ModePaiement();
        $modePaiement4 = new ModePaiement();
        // création d'un compte ticket
        $compteTicket = new CompteTicket();

        // test si le compte est actif
        $this->assertTrue($compteTicket->isActive());

        // création d'une opération de ticket
        $operation = new OperationTicket();

        // affectation des relations
        $operation->setCompte($compteTicket);
        $operation->setModePaiement($modePaiement3);
        $typeCompte->addModePaiement($modePaiement1);
        $typeCompte->addModePaiement($modePaiement2);
        $typeCompte->addModePaiement($modePaiement3);
        $typeCompte->addModePaiement($modePaiement4);
        $compteTicket->setType($typeCompte);

        return $operation;
    }

    /**
     * @uses vérifie que la méthode lève une exception si le nombre de ticket n'est pas
     * valide (<0) et si le paramètre de levée d'exception est égale à vrai (par defaut)
     * @param OperationTicketServiceTest $service
     * @depends testVideService
     * @covers OperationTicketServiceTest::isNbTicketValide
     */
    public function testFailIsNbTicketValide(OperationTicketService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::VALEURS_INCOHERENTES);

        // test de la méthode
        $service->isNbTicketValide(-4);
    }

    /**
     * @uses vérifie que la méthode lève une exception si le paramètre de levée d'exception
     * est égale à vrai (valeur par défaut) et dans le cas ou le compte ticket n'est pas
     * actif
     * @param OperationTicketService $service
     * @depends testVideService
     * @covers OperationTicketService::isTicketOperationValide
     */
    public function testFailIsTicketOperationValide1(OperationTicketService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        $operation = $this->testVideOperationTicket();
        // désactivation du compte ticket
        $compteTicket = $operation->getCompte();
        $compteTicket->setActive(false);

        // test de la méthode
        $service->isTicketOperationValide($operation);
    }

    /**
     * @uses vérifie que la méthode lève une exception si le paramètre de levée d'exception
     * est égale à vrai (valeur par défaut) et dans le cas ou le mode de paiement n'est pas
     * autorisé sur ce type de compte
     * @param OperationTicketService $service
     * @depends testVideService
     * @covers OperationTicketService::isTicketOperationValide
     */
    public function testFailIsTicketOperationValide2(OperationTicketService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        $operation = $this->testVideOperationTicket();
        // changement du mode de paiement de l'opération
        $modePaiement = new ModePaiement();
        $operation->setModePaiement($modePaiement);

        // test de la méthode
        $service->isTicketOperationValide($operation);
    }

    /**
     * @uses vérifie que la méthode lève une exception si le paramètre de levée d'exception
     * est égale à vrai (valeur par défaut) et dans le cas ou le nombre de ticket de l'opération
     * n'est pas valide
     * @param OperationTicketService $service
     * @depends testVideService
     * @covers OperationTicketService::isTicketOperationValide
     */
    public function testFailIsTicketOperationValide3(OperationTicketService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::VALEURS_INCOHERENTES);

        $operation = $this->testVideOperationTicket();
        // attribution d'un nombre de ticket égale à 0
        $operation->setNbTicket(0);

        // test de la méthode
        $service->isTicketOperationValide($operation);
    }

    /**
     * @uses vérifie que la méthode lève une exception si le paramètre de levée d'exception
     * est égale à vrai (valeur par défaut) et dans le cas ou le montant des ticket du compte
     * n'est pas valide
     * @param OperationTicketService $service
     * @depends testVideService
     * @covers OperationTicketService::isTicketOperationValide
     */
    public function testFailIsTicketOperationValide4(OperationTicketService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::VALEURS_INCOHERENTES);

        $operation = $this->testVideOperationTicket();
        // attribution d'un montant de ticket du compte négatif
        /** @var CompteTicket $compteTicket */
        $compteTicket = $operation->getCompte();
        $compteTicket->setMontantTicket(-15.23);

        // test de la méthode
        $service->isTicketOperationValide($operation);
    }

    /**
     * @uses vérifie que la méthode retourne vrai si l'opération de tiket est valide
     * quelque soit la valeur du paramètre de levée d'exception.
     * Elle doit égelement retourné faux si l'opération n'est pas valide et
     * que le paramètre de levée d'exception est égale à faux
     * @param OperationTicketService $service
     * @depends testVideService
     * @covers OperationTicketService::isTicketOperationValide
     */
    public function testIsTicketOperationValide(OperationTicketService $service)
    {
        $operation = $this->testVideOperationTicket();
        // test opération valide
        $operation->setNbTicket(4);
        /** @var CompteTicket $compte */
        $compte = $operation->getCompte();
        $compte->setMontantTicket(7.30);
        $compte->setActive(true);

        // test de la méthode, doit retourner vrai
        $this->assertTrue($service->isTicketOperationValide($operation));
        $this->assertTrue($service->isTicketOperationValide($operation, false));

        // test opération non-valide
        $operation->setNbTicket(3);
        $compte->setMontantTicket(5.50);
        $compte->setActive(false);

        // test de la méthode, doit retourner faux
        $this->assertFalse($service->isTicketOperationValide($operation, false));

        // test opération non-valide
        $operation->setNbTicket(9);
        $compte->setMontantTicket(2.50);
        $compte->setActive(true);
        // création d'un nouveau mode de paiement
        $modePaiement = new ModePaiement();
        $operation->setModePaiement($modePaiement);

        // test de la méthode, doit retourner faux
        $this->assertFalse($service->isTicketOperationValide($operation, false));
    }

    /**
     * @uses vérifie que la méthode retourne vrai si le nombre de ticket est valide,
     * quelque soit la valeur du paramètre de levée d'exception, et faux lorsque le
     * nombre de ticket n'est pas valide et que le paramètre de levée d'exception
     * est égale à faux.
     * @param OperationTicketServiceTest $service
     * @depends testVideService
     * @covers OperationTicketServiceTest::isNbTicketValide
     */
    public function testIsNbTicketValide(OperationTicketService $service)
    {
        // test retourne vrai
        $this->assertTrue($service->isNbTicketValide(4));
        $this->assertTrue($service->isNbTicketValide(4, false));

        // test retourne false
        $this->assertFalse($service->isNbTicketValide(-2, false));
        $this->assertFalse($service->isNbTicketValide(0, false));
    }

    /**
     * @uses vérifie que la méthode retourne met à jour le montant de l'opération
     * ticket en fonction du nombre de ticket ainsi que du montant des tickets du
     * compte ticket.
     * @depends testVideService
     * @dataProvider operationsProvider
     * @covers OperationTicketServiceTest::guessMontant
     * @param int                    $nbTicket
     * @param float                  $montantTicket
     * @param float                  $valeurAttendue
     * @param OperationTicketService $service
     */
    public function testGuessMontant(
        $nbTicket,
        $montantTicket,
        $valeurAttendue,
        OperationTicketService $service
    ) {
        // test pour un jeu de données
        // création d'un compte ticket
        $compteTicket = new CompteTicket();
        // affectation du montant des tickets
        $compteTicket->setMontantTicket($montantTicket);

        // création d'un opération de ticket
        $operationTicket = new OperationTicket();
        // affectation d'un nombre de ticket
        $operationTicket->setNbTicket($nbTicket);
        // affectation du compte à l'opration
        $operationTicket->setCompte($compteTicket);

        // utilisation du service
        $service->guessMontant($operationTicket);

        // test d'équivalence
        $this->assertEquals($valeurAttendue, $operationTicket->getMontant());
    }

    /**
     * @return array
     */
    public function operationsProvider()
    {
        return [
            [
                "nbTicket" => 2,
                "montantTicket" => 7.50,
                "valeurAttendue" => -15.0,
            ],
            [
                "nbTicket" => 1,
                "montantTicket" => 4.20,
                "valeurAttendue" => -4.20,
            ],
            [
                "nbTicket" => 8,
                "montantTicket" => 14.25,
                "valeurAttendue" => -114.0,
            ],
            [
                "nbTicket" => 3,
                "montantTicket" => 10.0,
                "valeurAttendue" => -30.0,
            ],
            [
                "nbTicket" => 45,
                "montantTicket" => 9.50,
                "valeurAttendue" => -427.50,
            ],
        ];
    }
}
