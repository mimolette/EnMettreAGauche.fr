<?php

namespace CoreBundle\Tests\Service\Compte;

use CoreBundle\Service\Compte\CompteTicketService;
use CoreBundle\Tests\Service\AbstractMasterService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * CompteTicketServiceTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * CompteTicketServiceTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class CompteTicketServiceTest extends AbstractMasterService
{
    /**
     * @return CompteTicketService
     */
    public function testVideService()
    {
        $this->setUp();

        return $this->get('emag.core.compte.ticket');
    }

    /**
     * @uses vérifie que la méthode lève un exception dans le cas ou le montant
     * n'est pas valide (<0) si le paramètre de levée d'exception est égale à
     * vrai (valeur par défaut)
     * @param CompteTicketService $service
     * @depends testVideService
     * @covers CompteTicketService::isMontantTicketValide
     */
    public function testFailIsMontantTicketValide(CompteTicketService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::VALEURS_INCOHERENTES);

        // test de la méthode
        $service->isMontantTicketValide(-15.23);
    }

    /**
     * @uses vérifie que la méthode retourne vrai si le montant est valide, quelque
     * soit la valeur du paramètre de levée d'exception. Elle doit également retournée
     * faux si le montant n'est pas valide et si le paramètre de levée d'exception est
     * égale à faux
     * @param CompteTicketService $service
     * @depends testVideService
     * @covers CompteTicketService::isMontantTicketValide
     */
    public function testIsMontantTicketValide(CompteTicketService $service)
    {
        // test retourne vrai
        $this->assertTrue($service->isMontantTicketValide(7.50));
        $this->assertTrue($service->isMontantTicketValide(7.50, false));

        // test retourne false
        $this->assertFalse($service->isMontantTicketValide(-2.5, false));
        $this->assertFalse($service->isMontantTicketValide(0, false));
    }
}
