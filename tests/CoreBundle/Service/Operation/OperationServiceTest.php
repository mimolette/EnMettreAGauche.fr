<?php

namespace CoreBundle\Tests\Service\Operation;

use CoreBundle\Entity\Compte;
use CoreBundle\Entity\ModePaiement;
use CoreBundle\Entity\OperationCourante;
use CoreBundle\Service\Operation\OperationService;
use CoreBundle\Tests\Service\AbstractMasterService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * OperationServiceTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * OperationServiceTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class OperationServiceTest extends AbstractMasterService
{
    /**
     * @return OperationService
     */
    public function testVideService()
    {
        $this->setUp();

        return $this->get('emag.core.operation');
    }

    /**
     * @uses vérifie si la méthode lève une exception dans le cas ou aucun compte
     * n'est trouvé dans l'objet opération
     * @depends testVideService
     * @param OperationService $service
     * @covers OperationService::getCompte
     */
    public function testFailGetCompte(OperationService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE);

        // création d'une nouvelle opération sans compte
        $operation = new OperationCourante();

        // test d'utilisation de la méthode
        $service->getCompte($operation);
    }

    /**
     * @uses vérifie si la méthode lève une exception dans le cas ou aucun mode de
     * paiement n'est trouvé dans l'objet opération
     * @depends testVideService
     * @param OperationService $service
     * @covers OperationService::getModePaiement
     */
    public function testFailGetModePaiement(OperationService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE);

        // création d'une nouvelle opération sans mode de paiement
        $operation = new OperationCourante();

        // test d'utilisation de la méthode
        $service->getModePaiement($operation);
    }

    /**
     * @uses vérifie que la méthode retourne bien l'objet Compte
     * @depends testVideService
     * @param OperationService $service
     * @covers OperationService::getCompte
     */
    public function testGetTypeCompte(OperationService $service)
    {
        // création d'une nouvelle opération
        $operation = new OperationCourante();

        // création d'un compte
        $compte = new Compte();
        $operation->setCompte($compte);

        // test d'utilisation de la méthode
        $this->assertEquals($compte, $service->getCompte($operation));
    }

    /**
     * @uses vérifie que la méthode retourne bien l'objet ModePaiement
     * @depends testVideService
     * @param OperationService $service
     * @covers OperationService::getModePaiement
     */
    public function testGetModePaiement(OperationService $service)
    {
        // création d'une nouvelle opération
        $operation = new OperationCourante();

        // création d'un mode de paiement
        $mode = new ModePaiement();
        $operation->setModePaiement($mode);

        // test d'utilisation de la méthode
        $this->assertEquals($mode, $service->getModePaiement($operation));
    }
}
