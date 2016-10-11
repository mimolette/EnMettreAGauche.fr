<?php

namespace CoreBundle\Tests\Service\Operation;

use CoreBundle\Entity\AjustementSolde;
use CoreBundle\Entity\Compte;
use CoreBundle\Entity\ModePaiement;
use CoreBundle\Entity\OperationCourante;
use CoreBundle\Service\Operation\AjustementService;
use CoreBundle\Service\Operation\OperationService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;
use MasterBundle\Test\AbstractMasterService;

/**
 * AjustementServiceTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * AjustementServiceTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class AjustementServiceTest extends AbstractMasterService
{
    /**
     * @return AjustementService
     */
    public function testVideService()
    {
        $this->setUp();

        return $this->get('emag.core.ajustement');
    }

    /**
     * @uses vérifie si la méthode lève une exception dans le cas ou aucun compte
     * n'est trouvé dans l'objet ajustement
     * @depends testVideService
     * @param AjustementService $service
     * @covers AjustementService::getCompte
     */
    public function testFailGetCompte(AjustementService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE);

        // création d'un nouvel ajustement
        $ajustement = new AjustementSolde();

        // test d'utilisation de la méthode
        $service->getCompte($ajustement);
    }

    /**
     * @uses vérifie que la méthode retourne bien l'objet Compte
     * @depends testVideService
     * @param AjustementService $service
     * @covers AjustementService::getCompte
     */
    public function testGetTypeCompte(AjustementService $service)
    {
        // création d'un nouvel ajustement
        $ajustement = new AjustementSolde();

        // création d'un compte
        $compte = new Compte();
        $ajustement->setCompte($compte);

        // test d'utilisation de la méthode
        $this->assertEquals($compte, $service->getCompte($ajustement));
    }
}
