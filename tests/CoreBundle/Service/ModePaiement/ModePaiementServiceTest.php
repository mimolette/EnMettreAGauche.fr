<?php

namespace CoreBundle\Tests\Service\ModePaiement;

use CoreBundle\Entity\Compte;
use CoreBundle\Entity\ModePaiement;
use CoreBundle\Entity\OperationCourante;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Service\ModePaiement\ModePaiementService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;
use MasterBundle\Test\AbstractMasterService;

/**
 * ModePaiementServiceTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * ModePaiementServiceTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class ModePaiementServiceTest extends AbstractMasterService
{
    /**
     * @return ModePaiementService
     */
    public function testVideService()
    {
        $this->setUp();

        return $this->get('emag.core.mode_paiement');
    }

    /**
     * @uses vérifie que la méthode retourne une exception dans le cas ou l'opération
     * n'est pas autorisé sur ce type de compte, car le paramètre de levée d'exception
     * est égale à vrai (comportement par défaut)
     * @param ModePaiementService $service
     * @depends testVideService
     * @covers ModePaiementService::isModePaiementAutorise
     */
    public function testFailIsModePaiementAutorise(ModePaiementService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création d'un compte avec un type de compte
        $compte = new Compte();
        $typeCompte = new TypeCompte();
        $compte->setType($typeCompte);

        // ajout de deux mode de paiement autorisé pour le type de compte
        $modePaiement1 = new ModePaiement();
        $modePaiement2 = new ModePaiement();
        $typeCompte->addModePaiement($modePaiement1);
        $typeCompte->addModePaiement($modePaiement2);

        // création d'une opération avec un autre mode de paiement
        $operation = new OperationCourante();
        $modePaiement3 = new ModePaiement();
        $operation->setModePaiement($modePaiement3);
        $operation->setCompte($compte);

        // test de la méthode
        $service->isModePaiementAutorise($operation);
    }

    /**
     * @uses vérifie que la méthode un booléen dans le cas ou l'opération est
     * autorisé sur ce type de compte (avec ou sans la levée d'exception) ainsi
     * que si l'opération n'es pas autorisé avec le paramètre de levée d'exception
     * égale à faux
     * @param ModePaiementService $service
     * @depends testVideService
     * @covers ModePaiementService::isModePaiementAutorise
     */
    public function testIsModePaiementAutorise(ModePaiementService $service)
    {
        // création d'un compte avec un type de compte
        $compte = new Compte();
        $typeCompte = new TypeCompte();
        $compte->setType($typeCompte);

        // ajout de deux mode de paiement autorisé pour le type de compte
        $modePaiement1 = new ModePaiement();
        $modePaiement2 = new ModePaiement();
        $typeCompte->addModePaiement($modePaiement1);
        $typeCompte->addModePaiement($modePaiement2);

        // création d'une opération avec un autre mode de paiement
        $operation = new OperationCourante();
        $modePaiement3 = new ModePaiement();
        $operation->setModePaiement($modePaiement3);
        $operation->setCompte($compte);

        // test de la méthode qui doit retourner faux
        $this->assertFalse($service->isModePaiementAutorise($operation, false));

        // changement du mode de paiement de l'opération pour un mode autorisé
        $operation->setModePaiement($modePaiement1);

        // test de la méthode qui doit retourner vrai
        $this->assertTrue($service->isModePaiementAutorise($operation));
        $this->assertTrue($service->isModePaiementAutorise($operation, true));
    }
}
