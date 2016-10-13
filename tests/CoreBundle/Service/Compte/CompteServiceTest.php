<?php

namespace CoreBundle\Tests\Service\Compte;

use CoreBundle\Entity\Compte;
use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Enum\TypeCompteEnum;
use CoreBundle\Service\Compte\CompteService;
use CoreBundle\Tests\Service\AbstractMasterService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * CompteServiceTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * CompteServiceTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class CompteServiceTest extends AbstractMasterService
{
    /**
     * @return CompteService
     */
    public function testVideService()
    {
        $this->setUp();

        return $this->get('emag.core.compte');
    }

    /**
     * @uses vérifie si la méthode lève une exception dans le cas ou aucun type
     * de compte n'est trouvé dans l'objet compte
     * @depends testVideService
     * @param CompteService $service
     * @covers CompteService::getTypeCompte
     */
    public function testFailGetTypeCompte(CompteService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE);

        // création d'un nouveau compte sans type de compte
        $compte = new Compte();

        // test d'utilisation de la méthode
        $service->getTypeCompte($compte);
    }

    /**
     * @uses vérifie que la méthode lève une exception si le nombre de ticket n'est pas
     * supérieur à 0
     * @param CompteService $service
     * @covers CompteService::addNbTicket
     * @depends testVideService
     */
    public function testFailAddNbTicket(CompteService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::VALEURS_INCOHERENTES);

        // création d'un compte ticket
        $compte = new CompteTicket();

        // test d'utilisation de la méthode
        $service->addNbTicket(-5, $compte);
    }

    /**
     * @uses vérifie si la méthode lève une exception dans le cas ou le compte est
     * inactif et que le paramètre pour spécifie la levée d'exception est vrai
     * @depends testVideService
     * @param CompteService $service
     * @covers CompteService::isCompteActif
     */
    public function testFailIsCompteActif(CompteService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création d'un nouveau compte inactif
        $compte = new Compte();
        $compte->setActive(false);

        // test d'utilisation de la méthode pour vérifier l'état d'un compte
        $service->isCompteActif($compte);
    }

    /**
     * @uses vérifie si la méthode lève une exception dans le cas ou aucun type de compte
     * n'est affecté au compte
     * @depends testVideService
     * @param CompteService $service
     * @covers CompteService::isAutoriseAuxAjustements
     */
    public function testFailIsAutoriseAuxAjustements1(CompteService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE);

        // création d'un nouveau compte
        $compte = new Compte();

        // test d'utilisation de la méthode sans affecté de type de compte
        $service->isAutoriseAuxAjustements($compte);
    }

    /**
     * @uses vérifie si la méthode lève une exception dans le cas ou le type de
     * compte n'autorise pas les ajustements et que le paramètre de levée d'exception
     * n'est pas spécifié donc vrai (true par défaut)
     * @depends testVideService
     * @param CompteService $service
     * @covers CompteService::isAutoriseAuxAjustements
     */
    public function testFailIsAutoriseAuxAjustements2(CompteService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création d'un nouveau compte
        $compte = new Compte();

        // création d'un type de compte qui n'autorise pas les ajustements
        $typeCompte = new TypeCompte();
        $typeCompte->setAutoriseAjustements(false);
        $compte->setType($typeCompte);

        // test d'utilisation de la méthode sans affecté de type de compte
        $service->isAutoriseAuxAjustements($compte);
    }

    /**
     * @uses vérifie si la méthode lève une exception dans le cas on tente de mettre à jour
     * le solde d'un compte, alors que son type ne l'autorise pas
     * @param CompteService $service
     * @depends testVideService
     * @covers CompteService::setNouveauSolde
     */
    public function testFailSetNouveauSolde(CompteService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::VALEURS_INCOHERENTES);

        // création d'un nouveau compte
        $compte = new Compte();

        // création d'un type de compte qui n'autorise pas les soldes négatifs
        $typeCompte = new TypeCompte();
        $typeCompte->setEtreNegatif(false);
        $compte->setType($typeCompte);

        // test d'utilisation de la méthode permettant d'affecter un nouveau solde
        $service->setNouveauSolde(-14.25, $compte);
    }

    /**
     * @uses vérifie que la méthode renvoi un booléen dans le cas ou le compte est
     * actif, mais aussi dans le cas ou celui-ci n'est pas actif si le paramètre
     * de levée d'exception est désactivé (false)
     * @param CompteService $service
     * @depends testVideService
     * @covers CompteService::isCompteActif
     */
    public function testIsComtpeActif(CompteService $service)
    {
        // création d'un nouveau compte actif
        $compte = new Compte();
        $compte->setActive(true);

        // test d'utilisation de la méthode pour vérifier l'état d'un compte
        // avec ou sans le paramètre de levée d'exception
        $this->assertTrue($service->isCompteActif($compte));
        $this->assertTrue($service->isCompteActif($compte, false));

        // modification de l'état du compte à inactif
        $compte->setActive(false);

        // test d'utilisation de la méthode pour vérifier l'état d'un compte
        $this->assertFalse($service->isCompteActif($compte, false));
    }

    /**
     * @uses vérifie si la méthode met à jour le solde du compte
     * @param CompteService $service
     * @depends testVideService
     * @covers CompteService::setNouveauSolde
     */
    public function testSetNouveauSolde(CompteService $service)
    {
        // création d'un nouveau compte avec un solde
        $compte = new Compte();
        $compte->setSolde(15.56);

        // création d'un type de compte qui n'autorise pas les soldes négatifs
        $typeCompte = new TypeCompte();
        $typeCompte->setEtreNegatif(false);
        $compte->setType($typeCompte);

        // test d'utilisation de la méthode permettant d'affecter un nouveau solde
        $service->setNouveauSolde(5.45, $compte);

        $this->assertEquals(5.45, $compte->getSolde());

        // changement du type de compte qui autorise désormais les solde négatif
        $typeCompte->setEtreNegatif(true);

        // test d'utilisation de la méthode permettant d'affecter un nouveau solde
        $service->setNouveauSolde(-5.45, $compte);

        $this->assertEquals(-5.45, $compte->getSolde());
    }

    /**
     * @uses vérifie qu'il est possible de mettre à jour le solde d'un compte inactif
     * @param CompteService $service
     * @depends testVideService
     * @covers CompteService::setNouveauSolde
     */
    public function testSetNouveauSoldeCompteInactif(CompteService $service)
    {
        // création d'un nouveau compte avec un solde
        $compte = new Compte();
        $compte->setSolde(45.56);
        $compte->setActive(false);

        // création d'un type de compte qui autorise les soldes négatifs
        $typeCompte = new TypeCompte();
        $typeCompte->setEtreNegatif(true);
        $compte->setType($typeCompte);

        // test d'utilisation de la méthode permettant d'affecter un nouveau solde
        $service->setNouveauSolde(14.20, $compte);

        $this->assertEquals(14.20, $compte->getSolde());
    }

    /**
     * @uses vérifie que la méthode retourne bien un booléen dans le cas ou le compte
     * est autorisé aux ajustement ou bien si le paramètre de levée d'exception est
     * spécifié à faux (false)
     * @depends testVideService
     * @param CompteService $service
     * @covers CompteService::isAutoriseAuxAjustements
     */
    public function testIsAutoriseAuxAjustements(CompteService $service)
    {
        // création d'un nouveau compte
        $compte = new Compte();

        // création d'un type de compte qui autorise les ajustements
        $typeCompte = new TypeCompte();
        $typeCompte->setAutoriseAjustements(true);
        $compte->setType($typeCompte);

        // vérification que la méthode retourne bien un booléen
        $this->assertTrue($service->isAutoriseAuxAjustements($compte));
        $this->assertTrue($service->isAutoriseAuxAjustements($compte, false));

        // changement pour un type de comtpe qui n'autorise pas les ajustements
        $typeCompte->setNumeroUnique(TypeCompteEnum::COMPTE_CHEQUE);

        // vérification que la méthode retourne bien un booléen
        $this->assertTrue($service->isAutoriseAuxAjustements($compte, false));
    }

    /**
     * @uses vérifie que la méthode retourne bien l'objet TypeCompte
     * @depends testVideService
     * @param CompteService $service
     * @covers CompteService::getTypeCompte
     */
    public function testGetTypeCompte(CompteService $service)
    {
        // création d'un nouveau compte
        $compte = new Compte();

        // création d'un type de compte
        $typeCompte = new TypeCompte();
        $compte->setType($typeCompte);

        // test d'utilisation de la méthode
        $this->assertEquals($typeCompte, $service->getTypeCompte($compte));
    }
}
