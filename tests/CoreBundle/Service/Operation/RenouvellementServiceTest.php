<?php

namespace CoreBundle\Tests\Service\Operation;

use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\Renouvellement;
use CoreBundle\Service\Operation\RenouvellementService;
use CoreBundle\Tests\Service\AbstractMasterService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * RenouvellementServiceTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * RenouvellementServiceTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class RenouvellementServiceTest extends AbstractMasterService
{
    /**
     * @return RenouvellementService
     */
    public function testVideService()
    {
        $this->setUp();

        return $this->get('emag.core.renouvellement');
    }

    /**
     * @uses vérifie si la méthode lève une exception dans le cas ou aucun compte
     * n'est trouvé dans l'objet renouvellement
     * @depends testVideService
     * @param RenouvellementService $service
     * @covers RenouvellementService::getCompte
     */
    public function testFailGetCompte(RenouvellementService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE);

        // création d'un nouveau renouvellement
        $renouvellement = new Renouvellement();

        // test d'utilisation de la méthode
        $service->getCompte($renouvellement);
    }

    /**
     * @uses vérifie que la méthode retourne bien l'objet Compte
     * @depends testVideService
     * @param RenouvellementService $service
     * @covers RenouvellementService::getCompte
     */
    public function testGetTypeCompte(RenouvellementService $service)
    {
        // création d'un nouvel ajustement
        $renouvellement = new Renouvellement();

        // création d'un compte
        $compte = new CompteTicket();
        $renouvellement->setCompte($compte);

        // test d'utilisation de la méthode
        $this->assertEquals($compte, $service->getCompte($renouvellement));
    }
}
