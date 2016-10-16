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
}
