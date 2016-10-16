<?php

namespace CoreBundle\Tests\Service\Operation;

use CoreBundle\Entity\Compte;
use CoreBundle\Entity\ModePaiement;
use CoreBundle\Entity\OperationCourante;
use CoreBundle\Entity\TypeCompte;
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
     * @uses retourne une opération lié à un compte, type de compte,
     * mode de paiement
     * @return OperationCourante
     */
    public function testVideOperation()
    {
        // création d'un type de compte
        $typeCompte = new TypeCompte();
        // création de mode de paiements
        $modePaiement1 = new ModePaiement();
        $modePaiement2 = new ModePaiement();
        $modePaiement3 = new ModePaiement();
        $modePaiement4 = new ModePaiement();
        // création d'un compte
        $compte = new Compte();
        // test si le compte est actif par défaut
        $this->assertTrue($compte->isActive());
        // création d'un opération courante
        $operation = new OperationCourante();

        // affectation des relations
        $operation->setCompte($compte);
        $operation->setModePaiement($modePaiement3);
        $typeCompte->addModePaiement($modePaiement1);
        $typeCompte->addModePaiement($modePaiement2);
        $typeCompte->addModePaiement($modePaiement3);
        $typeCompte->addModePaiement($modePaiement4);
        $compte->setType($typeCompte);

        return $operation;
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
    public function testGetCompte(OperationService $service)
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

    /**
     * @uses vérifie que la méthode retourne vrai si l'opération courante est valide
     * quelque soit la valeur du paramètre de levée d'exception.
     * Elle doit égelement retourné faux si l'opération n'est pas valide et
     * que le paramètre de levée d'exception est égale à faux
     * @param OperationService $service
     * @depends testVideService
     * @covers OperationService::isOperationValide
     */
    public function testIsOperationValide(OperationService $service)
    {
        $operation = $this->testVideOperation();
        // test opération valide
        $operation->setMontant(-14.52);
        /** @var Compte $compte */
        $compte = $operation->getCompte();
        $compte->setSolde(7.30);
        $modePaiement = $operation->getModePaiement();
        // le mode de paiement autorise les opération en négatif
        $modePaiement->setEtreNegatif(true);
        $typeCompte = $compte->getType();
        // le type de compte autorise les solde en négatif
        $typeCompte->setEtreNegatif(true);

        // test de la méthode, doit retourner vrai
        $this->assertTrue($service->isOperationValide($operation));
        $this->assertTrue($service->isOperationValide($operation, false));

        // test opération non-valide
        // Le mode de paiement n'autorise plus les opération négative
        $modePaiement->setEtreNegatif(false);

        // test de la méthode, doit retourner faux
        $this->assertFalse($service->isOperationValide($operation, false));

        // test opération non-valide
        // le compte n'est plus actif
        $compte->setActive(false);
        // l'opération est positive
        $operation->setMontant(58.26);

        // test de la méthode, doit retourner faux
        $this->assertFalse($service->isOperationValide($operation, false));
    }
}
