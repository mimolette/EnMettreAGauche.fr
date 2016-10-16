<?php

namespace CoreBundle\Tests\Service\Compte;

use CoreBundle\Entity\Chequier;
use CoreBundle\Service\Compte\ChequierService;
use CoreBundle\Tests\Service\AbstractMasterService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * ChequierServiceTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * ChequierServiceTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class ChequierServiceTest extends AbstractMasterService
{
    /**
     * @return ChequierService
     */
    public function testVideService()
    {
        $this->setUp();

        return $this->get('emag.core.chequier');
    }

    /**
     * @uses vérifie que la méthode lève une exception si le chequier n'est pas actif
     * @param ChequierService $service
     * @depends testVideService
     * @covers ChequierService::isChequierActif
     */
    public function testFailIsChequierActif(ChequierService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création d'un chequier inactif
        $chequier = new Chequier();
        $chequier->setActive(false);

        // test de la méthode
        $service->isChequierActif($chequier);
    }

    /**
     * @uses vérifie que la méthode lève une exception si le chequier ne possede un
     * nombre de chèque inférieur à 0 et si le paramètre de levée d'exception est
     * égale à vrai (valeur par défaut)
     * @param ChequierService $service
     * @depends testVideService
     * @covers ChequierService::utiliseUnCheque
     */
    public function testFailUtiliseUnCheque1(ChequierService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création d'un chequier avec un nombre de chèque inférieur à 0
        $chequier = new Chequier();
        $chequier->setNbCheques(-1);

        // test de la méthode
        $service->utiliseUnCheque($chequier);
    }

    /**
     * @uses vérifie que la méthode lève une exception si le chequier ne possede un
     * nombre de chèque inférieur égale à 0 et si le paramètre de levée d'exception est
     * égale à vrai (valeur par défaut)
     * @param ChequierService $service
     * @depends testVideService
     * @covers ChequierService::utiliseUnCheque
     */
    public function testFailUtiliseUnCheque2(ChequierService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création d'un chequier avec un nombre de chèque inférieur égale à 0
        $chequier = new Chequier();
        $chequier->setNbCheques(0);

        // test de la méthode
        $service->utiliseUnCheque($chequier);
    }

    /**
     * @uses vérifie que la méthode retourne vrai si le chequier est actif dans le cas ou
     * le paramètre de levée d'exception est égale à vrai (valeur par defaut)
     * @param ChequierService $service
     * @depends testVideService
     * @covers ChequierService::isChequierActif
     */
    public function testIsChequierActif1(ChequierService $service)
    {
        // création d'un chequier (actif par défaut)
        $chequier = new Chequier();

        // test de la méthode
        $this->assertTrue($service->isChequierActif($chequier));
    }

    /**
     * @uses vérifie que la méthode retourne vrai si le chequier est actif dans le cas ou
     * le paramètre de levée d'exception est égale à faux
     * @param ChequierService $service
     * @depends testVideService
     * @covers ChequierService::isChequierActif
     */
    public function testIsChequierActif2(ChequierService $service)
    {
        // création d'un chequier (actif par défaut)
        $chequier = new Chequier();

        // test de la méthode
        $this->assertTrue($service->isChequierActif($chequier, false));
    }

    /**
     * @uses vérifie que la méthode retourne faux si le chequier est inactif dans le cas ou
     * le paramètre de levée d'exception est désactivé (égale à faux)
     * @param ChequierService $service
     * @depends testVideService
     * @covers ChequierService::isChequierActif
     */
    public function testIsChequierActif3(ChequierService $service)
    {
        // création d'un chequier inactif
        $chequier = new Chequier();
        $chequier->setActive(false);

        // test de la méthode
        $this->assertFalse($service->isChequierActif($chequier, false));
    }

    /**
     * @uses vérifie que la méthode met à jour le nombre de chèque du chequier à 4
     * si celui-ci en posséde 5 avant l'utilisation de la méthode
     * @param ChequierService $service
     * @depends testVideService
     * @covers ChequierService::utiliseUnCheque
     */
    public function testUtiliseUnCheque1(ChequierService $service)
    {
        // création d'un chequier avec 5 chèques
        $chequier = new Chequier();
        $chequier->setNbCheques(5);

        // utilisation de la méthode
        $service->utiliseUnCheque($chequier);

        // test si le nombre de chèque est égale à 4
        $this->assertEquals(4, $chequier->getNbCheques());
    }

    /**
     * @uses vérifie que le chequier est toujours actif après l'utilisation de la méthode
     * si celui-ci contenait plus de 1 chèque disponible
     * @param ChequierService $service
     * @depends testVideService
     * @covers ChequierService::utiliseUnCheque
     */
    public function testUtiliseUnCheque2(ChequierService $service)
    {
        // création d'un chequier actif avec 2 chèques
        $chequier = new Chequier();
        $chequier->setNbCheques(2);

        // utilisation de la méthode
        $service->utiliseUnCheque($chequier);

        // test si le chequier est actif
        $this->assertTrue($chequier->isActive());
    }

    /**
     * @uses vérifie que le nombre de chèque disponible est égale à 0 si le compte
     * ne possède qu'un chèque disponible avant l'utilisation de la méthode
     * @param ChequierService $service
     * @depends testVideService
     * @covers ChequierService::utiliseUnCheque
     */
    public function testUtiliseUnCheque3(ChequierService $service)
    {
        // création d'un chequier avec 1 chèque
        $chequier = new Chequier();
        $chequier->setNbCheques(1);

        // utilisation de la méthode
        $service->utiliseUnCheque($chequier);

        // test si le nombre de chèque est égale à 0
        $this->assertEquals(0, $chequier->getNbCheques());
    }

    /**
     * @uses vérifie que le chequier est inactif après l'utilisation de la méthode
     * si celui-ci ne possédait q'un seul chèque
     * @param ChequierService $service
     * @depends testVideService
     * @covers ChequierService::utiliseUnCheque
     */
    public function testUtiliseUnCheque4(ChequierService $service)
    {
        // création d'un chequier avec 1 chèque
        $chequier = new Chequier();
        $chequier->setNbCheques(1);

        // utilisation de la méthode
        $service->utiliseUnCheque($chequier);

        // test si le chequier est inactif
        $this->assertFalse($chequier->isActive());
    }

    /**
     * @uses vérifie que le chequier est inactif après l'utilisation de la méthode
     * 3 fois d'affiler si le compte possédaait 3 chèques disponible
     * @param ChequierService $service
     * @depends testVideService
     * @covers ChequierService::utiliseUnCheque
     */
    public function testUtiliseUnCheque5(ChequierService $service)
    {
        // création d'un chequier avec 3 chèques
        $chequier = new Chequier();
        $chequier->setNbCheques(3);

        // utilisation de la méthode 3 fois
        $service->utiliseUnCheque($chequier);
        $service->utiliseUnCheque($chequier);
        $service->utiliseUnCheque($chequier);

        // test si le chequier est inactif
        $this->assertFalse($chequier->isActive());
    }

    /**
     * @uses vérifie que la méthode retourne vrai si le nombre de cheques du chequier
     * est égale à 1 (valide) et si le paramètre de levée d'exception est égale à vrai
     * (valeur par défaut)
     * @param ChequierService $service
     * @depends testVideService
     * @covers ChequierService::isNbChequeValidePourOperation
     */
    public function testIsNbChequeValidePourOperation1(ChequierService $service)
    {
        // création d'un chequier avec 1 chèque
        $chequier = new Chequier();
        $chequier->setNbCheques(1);

        // test de la méthode
        $this->assertTrue($service->isNbChequeValidePourOperation($chequier));
    }

    /**
     * @uses vérifie que la méthode retourne vrai si le nombre de cheques du chequier
     * est égale à 4 (valide) et si le paramètre de levée d'exception est égale à faux
     * @param ChequierService $service
     * @depends testVideService
     * @covers ChequierService::isNbChequeValidePourOperation
     */
    public function testIsNbChequeValidePourOperation2(ChequierService $service)
    {
        // création d'un chequier avec 1 chèque
        $chequier = new Chequier();
        $chequier->setNbCheques(4);

        // test de la méthode
        $this->assertTrue($service->isNbChequeValidePourOperation($chequier));
    }

    /**
     * @uses vérifie que la méthode retourne faux si le nombre de cheques du chequier
     * est égale à 0 (invalide) et si le paramètre de levée d'exception est égale à faux
     * @param ChequierService $service
     * @depends testVideService
     * @covers ChequierService::isNbChequeValidePourOperation
     */
    public function testIsNbChequeValidePourOperation3(ChequierService $service)
    {
        // création d'un chequier avec 1 chèque
        $chequier = new Chequier();
        $chequier->setNbCheques(0);

        // test de la méthode
        $this->assertFalse($service->isNbChequeValidePourOperation($chequier, false));
    }

    /**
     * @uses vérifie que la méthode retourne faux si le nombre de cheques du chequier
     * est égale à -1 (invalide) et si le paramètre de levée d'exception est égale à faux
     * @param ChequierService $service
     * @depends testVideService
     * @covers ChequierService::isNbChequeValidePourOperation
     */
    public function testIsNbChequeValidePourOperation4(ChequierService $service)
    {
        // création d'un chequier avec 1 chèque
        $chequier = new Chequier();
        $chequier->setNbCheques(-1);

        // test de la méthode
        $this->assertFalse($service->isNbChequeValidePourOperation($chequier, false));
    }
}
