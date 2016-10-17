<?php

namespace CoreBundle\Tests\Service\Operation;

use CoreBundle\Entity\Compte;
use CoreBundle\Entity\ModePaiement;
use CoreBundle\Entity\OperationCheque;
use CoreBundle\Entity\OperationCourante;
use CoreBundle\Entity\OperationTicket;
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

    /**
     * @uses vérifie que la méthode retourne vrai dans le cas ou l'opération doit être
     * comptabilisée car la date de l'opération est inférieure à la date du jour
     * @param OperationService $service
     * @depends testVideService
     * @covers OperationService::isOperationDoitEtreComptabilisee
     */
    public function testIsOperationDoitEtreComptabilisee1(OperationService $service)
    {
        // création d'un nouvelle opération
        $operation = new OperationCourante();

        // utilisation d'une date inférieure à la date du jour
        $operation->setDate($this->getDateDiffDateJour(-1));

        // test de la méthode
        $this->assertTrue($service->isOperationDoitEtreComptabilisee($operation));
    }

    /**
     * @uses vérifie que la méthode retourne faux dans le cas ou l'opération ne doit pas être
     * comptabilisée car la date de l'opération est supérieure à la date du jour
     * @param OperationService $service
     * @depends testVideService
     * @covers OperationService::isOperationDoitEtreComptabilisee
     */
    public function testIsOperationDoitEtreComptabilisee2(OperationService $service)
    {
        // création d'un nouvelle opération
        $operation = new OperationCourante();

        // utilisation d'une date supérieure à la date du jour
        $operation->setDate($this->getDateDiffDateJour(1));

        // test de la méthode
        $this->assertFalse($service->isOperationDoitEtreComptabilisee($operation));
    }

    /**
     * @uses vérifie que la méthode retourne vrai dans le cas ou l'opération doit être
     * comptabilisée car la date de l'opération est égale à la date du jour
     * @param OperationService $service
     * @depends testVideService
     * @covers OperationService::isOperationDoitEtreComptabilisee
     */
    public function testIsOperationDoitEtreComptabilisee3(OperationService $service)
    {
        // création d'un nouvelle opération
        $operation = new OperationCourante();

        // utilisation d'une date égale à la date du jour
        $operation->setDate(new \DateTime());

        // test de la méthode
        $this->assertTrue($service->isOperationDoitEtreComptabilisee($operation));
    }

    /**
     * @uses vérifie que la méthode retourne faux dans le cas ou l'opération ne doit pas être
     * comptabilisée car l'opération à déja été comptabilisée même si la date de l'opération
     * est égale à la date du jour
     * @param OperationService $service
     * @depends testVideService
     * @covers OperationService::isOperationDoitEtreComptabilisee
     */
    public function testIsOperationDoitEtreComptabilisee4(OperationService $service)
    {
        // création d'un nouvelle opération
        $operation = new OperationCourante();

        // utilisation d'une date égale à la date du jour
        $operation->setDate(new \DateTime());
        // l'opération à déja été comptabilisé
        $operation->setComptabilise(true);

        // test de la méthode
        $this->assertFalse($service->isOperationDoitEtreComptabilisee($operation));
    }

    /**
     * @uses vérifie que la méthode retourne faux dans le cas ou l'opération ne doit pas être
     * comptabilisée car l'opération à déja été comptabilisée même si la date de l'opération
     * est inférieure à la date du jour
     * @param OperationService $service
     * @depends testVideService
     * @covers OperationService::isOperationDoitEtreComptabilisee
     */
    public function testIsOperationDoitEtreComptabilisee5(OperationService $service)
    {
        // création d'un nouvelle opération
        $operation = new OperationCourante();

        // utilisation d'une date inférieure à la date du jour
        $operation->setDate($this->getDateDiffDateJour(-2));
        // l'opération à déja été comptabilisé
        $operation->setComptabilise(true);

        // test de la méthode
        $this->assertFalse($service->isOperationDoitEtreComptabilisee($operation));
    }

    /**
     * @uses vérifie que la méthode retourne faux dans le cas ou l'opération est de type chèque
     * et que celle-ci n'est pas encaissée même si la date de l'opération est inférieure à
     * la date du jour
     * @param OperationService $service
     * @depends testVideService
     * @covers OperationService::isOperationDoitEtreComptabilisee
     */
    public function testIsOperationDoitEtreComptabilisee6(OperationService $service)
    {
        // création d'un nouvelle opération chèque
        $operation = new OperationCheque();

        // utilisation d'une date inférieure à la date du jour
        $operation->setDate($this->getDateDiffDateJour(-2));
        // l'opération à déja été comptabilisé
        $operation->setEncaisse(false);

        // test de la méthode
        $this->assertFalse($service->isOperationDoitEtreComptabilisee($operation));
    }

    /**
     * @uses vérifie que le montant de l'opération courante est égale à -98.60 dans le
     * cas ou celle-ci est lié à un mode de paiement qui n'autorise que les opérations
     * négatives et si le montant le l'opération est égale à 98.60 intitialement
     * @param OperationService $service
     * @depends testVideService
     * @covers OperationService::devinerSigneOperation
     */
    public function testDevinerSigneOperation1(OperationService $service)
    {
        // création du mode de paiement
        $modePaiement = new ModePaiement();
        $modePaiement->setEtreNegatif(true);
        $modePaiement->setEtrePositif(false);

        // création de l'opération
        $operation = new OperationCourante();
        $operation->setMontant(98.60);

        // ajout du mode de paiement à l'opération
        $operation->setModePaiement($modePaiement);

        // utilisation de la méthode pour deviner le signe de l'opération
        $service->devinerSigneOperation($operation);

        // test d'égalité
        $this->assertEquals(-98.60, $operation->getMontant());
    }

    /**
     * @uses vérifie que le montant de l'opération chèque est égale à -125.24 dans le
     * cas ou celle-ci est lié à un mode de paiement qui autorise des opérations
     * négatives ou positives et si le montant le l'opération est égale à -125.24
     * intitialement
     * @param OperationService $service
     * @depends testVideService
     * @covers OperationService::devinerSigneOperation
     */
    public function testDevinerSigneOperation2(OperationService $service)
    {
        // création du mode de paiement
        $modePaiement = new ModePaiement();
        $modePaiement->setEtreNegatif(true);
        $modePaiement->setEtrePositif(true);

        // création de l'opération
        $operation = new OperationCheque();
        $operation->setMontant(-125.24);

        // ajout du mode de paiement à l'opération
        $operation->setModePaiement($modePaiement);

        // utilisation de la méthode pour deviner le signe de l'opération
        $service->devinerSigneOperation($operation);

        // test d'égalité
        $this->assertEquals(-125.24, $operation->getMontant());
    }

    /**
     * @uses vérifie que le nombre de ticket de l'opération de ticket est égale à 5
     * dans le cas ou le montant initiale est égale à -5
     * @param OperationService $service
     * @depends testVideService
     * @covers OperationService::devinerSigneOperation
     */
    public function testDevinerSigneOperation3(OperationService $service)
    {
        // création de l'opération
        $operation = new OperationTicket();
        $operation->setNbTicket(-5);

        // utilisation de la méthode pour deviner le signe de l'opération
        $service->devinerSigneOperation($operation);

        // test d'égalité
        $this->assertEquals(5, $operation->getNbTicket());
    }
}
