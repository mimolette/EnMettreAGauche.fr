<?php

namespace CoreBundle\Tests\Success\Service\Operation;

use CoreBundle\Entity\Chequier;
use CoreBundle\Entity\OperationCheque;
use CoreBundle\Service\Operation\OperationChequeService;
use CoreBundle\Tests\Service\AbstractMasterService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * OperationChequeServiceTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * OperationChequeServiceTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class OperationChequeServiceTest extends AbstractMasterService
{
    /**
     * @return OperationChequeService
     */
    public function testVideService()
    {
        $this->setUp();

        return $this->get('emag.core.operation.cheque');
    }

    /**
     * @uses retourne une opération de cheque lié à un chequier actif
     * @return OperationCheque
     */
    public function testVideOperation()
    {
        // création de l'opération de cheque
        $operation = new OperationCheque();
        // création d'un chequier actif
        $chequier = new Chequier();
        // test si le chequier est actif
        $this->assertTrue($chequier->isActive());

        // affectation du chequier à l'opération
        $operation->setChequier($chequier);

        return $operation;
    }

    /**
     * @uses vérifie si la méthode lève une exception dans le cas le chequier de l'opération
     * n'est pas actif et que la paramètre de levée d'exception est égale à vrai
     * (valeur par défaut)
     * @depends testVideService
     * @param OperationChequeService $service
     * @covers OperationChequeService::isOperationChequeValide
     */
    public function testFailIsOperationChequeValide1(OperationChequeService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        $operation = $this->testVideOperation();
        // affectation d'un montant négatif (chèque emis)
        $operation->setMontant(-8.5);
        // on met le chequier inactif
        $chequier = $operation->getChequier();
        $chequier->setActive(false);

        // test de la méthode
        $service->isOperationChequeValide($operation);
    }

    /**
     * @uses vérifie si la méthode lève une exception dans le cas le chequier de l'opération
     * contient un nombre de chèque égale à 0 (invalide) et que la paramètre de levée
     * d'exception est égale à vrai (valeur par défaut)
     * @depends testVideService
     * @param OperationChequeService $service
     * @covers OperationChequeService::isOperationChequeValide
     */
    public function testFailIsOperationChequeValide2(OperationChequeService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        $operation = $this->testVideOperation();
        // affectation d'un montant négatif (chèque emis)
        $operation->setMontant(-8.5);
        // on met le nombre de chèque égale à 0
        $chequier = $operation->getChequier();
        $chequier->setNbCheques(0);

        // test de la méthode
        $service->isOperationChequeValide($operation);
    }

    /**
     * @uses vérifie si la méthode lève une exception dans le cas l'opération n'est lié à
     * aucun chèquier
     * @depends testVideService
     * @param OperationChequeService $service
     * @covers OperationChequeService::isOperationChequeValide
     */
    public function testFailIsOperationChequeValide3(OperationChequeService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE);

        $operation = $this->testVideOperation();
        // affectation d'un montant négatif (chèque emis)
        $operation->setMontant(-8.5);
        // on supprime la liaison avec le chequier
        $operation->setChequier(null);

        // test de la méthode
        $service->isOperationChequeValide($operation);
    }

    /**
     * @uses vérifie si la méthode lève une exception dans le cas l'opération n'est lié à
     * un chèquier si l'opération est positive (chèque reçu)
     * @depends testVideService
     * @param OperationChequeService $service
     * @covers OperationChequeService::isOperationChequeValide
     */
    public function testFailIsOperationChequeValide4(OperationChequeService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        $operation = $this->testVideOperation();
        // affectation d'un montant positif (chèque reçu)
        $operation->setMontant(15.26);

        // test de la méthode
        $service->isOperationChequeValide($operation);
    }

    /**
     * @uses vérifie si la méthode lève une exception dans le cas ou aucun chequier
     * n'est trouvé dans l'objet OperationCheque
     * @depends testVideService
     * @param OperationChequeService $service
     * @covers OperationChequeService::getChequier
     */
    public function testFailGetChequier(OperationChequeService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE);

        // création d'une nouvelle opération de chèque
        $operation = new OperationCheque();

        // test d'utilisation de la méthode
        $service->getChequier($operation);
    }


    /**
     * @uses vérifie que la méthode retourne bien l'objet Chequier
     * @depends testVideService
     * @param OperationChequeService $service
     * @covers OperationChequeService::getChequier
     */
    public function testGetChequier(OperationChequeService $service)
    {
        // création d'une nouvelle opération de chèque
        $operation = new OperationCheque();

        // création d'un nouveau chequier
        $chequier = new Chequier();
        $operation->setChequier($chequier);

        // test d'utilisation de la méthode
        $this->assertEquals($chequier, $service->getChequier($operation));
    }

    /**
     * @uses vérifie si la méthode retourne vrai si l'opération de cheque est valide
     * et si le paramètre de levée d'exception est égale à vrai (valeur par défaut)
     * @depends testVideService
     * @param OperationChequeService $service
     * @covers OperationChequeService::isOperationChequeValide
     */
    public function testIsOperationChequeValide1(OperationChequeService $service)
    {
        $operation = $this->testVideOperation();
        // affectation d'un montant négatif (chèque emis)
        $operation->setMontant(-98.56);
        // attribut un nombre de chèques positif au chequier
        $chequier = $operation->getChequier();
        $chequier->setNbCheques(4);

        // test de la méhode
        $this->assertTrue($service->isOperationChequeValide($operation));
    }

    /**
     * @uses vérifie si la méthode retourne vrai si l'opération de cheque est valide
     * et si le paramètre de levée d'exception est égale à faux dans le cas d'un chèque émis
     * @depends testVideService
     * @param OperationChequeService $service
     * @covers OperationChequeService::isOperationChequeValide
     */
    public function testIsOperationChequeValide2(OperationChequeService $service)
    {
        $operation = $this->testVideOperation();
        // affectation d'un montant négatif (chèque emis)
        $operation->setMontant(-15.0);
        // attribut un nombre de chèques positif au chequier
        $chequier = $operation->getChequier();
        $chequier->setNbCheques(1);

        // test de la méhode
        $this->assertTrue($service->isOperationChequeValide($operation, false));
    }

    /**
     * @uses vérifie si la méthode retourne faux si l'opération de cheque est invalide
     * car le chequier est inactif et si le paramètre de levée d'exception est égale
     * à faux (dans le cas d'un chèque émis)
     * @depends testVideService
     * @param OperationChequeService $service
     * @covers OperationChequeService::isOperationChequeValide
     */
    public function testIsOperationChequeValide3(OperationChequeService $service)
    {
        $operation = $this->testVideOperation();
        // affectation d'un montant négatif (chèque emis)
        $operation->setMontant(-8.5);
        // attribut un nombre de chèques positif au chequier
        $chequier = $operation->getChequier();
        $chequier->setActive(false);

        // test de la méhode
        $this->assertFalse($service->isOperationChequeValide($operation, false));
    }

    /**
     * @uses vérifie si la méthode retourne faux si l'opération de cheque est invalide
     * car le nombre de chèque du chequier est égale à 0 et si le paramètre de
     * levée d'exception est égale à faux
     * @depends testVideService
     * @param OperationChequeService $service
     * @covers OperationChequeService::isOperationChequeValide
     */
    public function testIsOperationChequeValide4(OperationChequeService $service)
    {
        $operation = $this->testVideOperation();
        // affectation d'un montant négatif (chèque emis)
        $operation->setMontant(-50.5);
        // attribut un nombre de chèques positif au chequier
        $chequier = $operation->getChequier();
        $chequier->setNbCheques(0);

        // test de la méhode
        $this->assertFalse($service->isOperationChequeValide($operation, false));
    }

    /**
     * @uses vérifie si la méthode retourne vrai dans le cas ou le montant de l'opération
     * est positif (donc chèque reçu) et si l'opération n'est lié à aucun chéquier
     * @depends testVideService
     * @param OperationChequeService $service
     * @covers OperationChequeService::isOperationChequeValide
     */
    public function testIsOperationChequeValide5(OperationChequeService $service)
    {
        $operation = new OperationCheque();
        // affectation d'un montant positif (chèque reçu)
        $operation->setMontant(80.0);

        // test de la méhode
        $this->assertTrue($service->isOperationChequeValide($operation, false));
        $this->assertTrue($service->isOperationChequeValide($operation));
    }

    /**
     * @uses vérifie si la méthode retourne faux si l'opération de cheque est invalide
     * car le montant est positif et l'opération est quand même lié à un chequier dans
     * le cas ou le paramètre de levée d'exception est égale à faux
     * @depends testVideService
     * @param OperationChequeService $service
     * @covers OperationChequeService::isOperationChequeValide
     */
    public function testIsOperationChequeValide6(OperationChequeService $service)
    {
        $operation = $this->testVideOperation();
        // affectation d'un montant positif (chèque reçu)
        $operation->setMontant(120.0);

        // test de la méhode
        $this->assertFalse($service->isOperationChequeValide($operation, false));
    }
}
