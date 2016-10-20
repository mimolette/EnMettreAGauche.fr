<?php

namespace CoreBundle\Tests\Success\Service\Operation;

use CoreBundle\Entity\Compte;
use CoreBundle\Entity\ModePaiement;
use CoreBundle\Entity\TransfertArgent;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Enum\ModePaiementEnum;
use CoreBundle\Enum\TypeCompteEnum;
use CoreBundle\Service\Operation\TransfertArgentService;
use CoreBundle\Tests\Service\AbstractMasterService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * TransfertArgentServiceTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * TransfertArgentServiceTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class TransfertArgentServiceTest extends AbstractMasterService
{
    /**
     * @return TransfertArgentService
     */
    public function testVideService()
    {
        $this->setUp();

        return $this->get('emag.core.operation.tranfert_argent');
    }

    /**
     * @uses retourne un transfert d'argent lié à un compte débiteur, créditeur,
     * leurs type de compte et des modes de paiements
     * @return TransfertArgent
     */
    public function testVideTransfertArgent()
    {
        // création du compte débiteur
        $compteDebiteur = new Compte();
        // création du type de compte
        $typeCompteDebiteur = new TypeCompte();
        // création de mode de paiements
        $modePaiement1 = new ModePaiement();
        $modePaiement2 = new ModePaiement();
        $modePaiement3 = new ModePaiement();
        $modePaiement4 = new ModePaiement();
        $typeCompteDebiteur->addModePaiement($modePaiement1);
        $typeCompteDebiteur->addModePaiement($modePaiement2);
        $typeCompteDebiteur->addModePaiement($modePaiement3);
        $typeCompteDebiteur->addModePaiement($modePaiement4);
        $compteDebiteur->setType($typeCompteDebiteur);

        // test si le comtpe est actif
        $this->assertTrue($compteDebiteur->isActive());

        // création du compte créditeur
        $compteCrediteur = new Compte();
        // création du type de compte
        $typeCompteCrediteur = new TypeCompte();
        $typeCompteCrediteur->addModePaiement($modePaiement1);
        $typeCompteCrediteur->addModePaiement($modePaiement2);
        $typeCompteCrediteur->addModePaiement($modePaiement3);
        $compteCrediteur->setType($typeCompteCrediteur);

        // test si le compte est actif
        $this->assertTrue($compteCrediteur->isActive());

        // création d'un transfert d'argent
        $transfert = new TransfertArgent();
        $transfert->setCompte($compteDebiteur);
        $transfert->setCompteCrediteur($compteCrediteur);
        $transfert->setModePaiement($modePaiement2);

        return $transfert;
    }

    /**
     * @uses vérifie que la méthode lève une exception si le paramètre de levée d'exception
     * est égale à vrai (valeur par défaut) dans le cas ou le comtpte créditeur est incatif.
     * @param TransfertArgentService $service
     * @depends testVideService
     * @covers TransfertArgentService::isTransfertArgentValide
     */
    public function testFailIsTransfertArgentValide1(TransfertArgentService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        $transfert = $this->testVideTransfertArgent();
        // désactivation du compte créditeur
        $compteCrediteur = $transfert->getCompteCrediteur();
        $compteCrediteur->setActive(false);
        
        // test de la méthode
        $service->isTransfertArgentValide($transfert);
    }

    /**
     * @uses vérifie que la méthode lève une exception si le paramètre de levée d'exception
     * est égale à vrai (valeur par défaut) dans le cas ou le type du compte créditeur
     * n'autorise pas le mode de paiement du transfert d'argent
     * @param TransfertArgentService $service
     * @depends testVideService
     * @covers TransfertArgentService::isTransfertArgentValide
     */
    public function testFailIsTransfertArgentValide2(TransfertArgentService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        $transfert = $this->testVideTransfertArgent();
        // changement du mode paiement du transfert et ajout au type de compte du compte
        // débiteur mais pas sur le compte créditeur
        $nouveauMode = new ModePaiement();
        $transfert->setModePaiement($nouveauMode);
        $compteDebiteur = $transfert->getCompte();
        $typeCompteDebiteur = $compteDebiteur->getType();
        $typeCompteDebiteur->addModePaiement($nouveauMode);

        // test de la méthode
        $service->isTransfertArgentValide($transfert);
    }

    /**
     * @uses vérifie que la méthode lève une exception si le paramètre de levée d'exception
     * est égale à vrai (valeur par défaut) dans le cas ou l'association entre comtpe
     * type du compte débiteur, créditeur et mode de paiement du transfert d'argent
     * n'est pas valide.
     * @param TransfertArgentService $service
     * @depends testVideService
     * @covers TransfertArgentService::isTransfertArgentValide
     */
    public function testFailIsTransfertArgentValide3(TransfertArgentService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        $transfert = $this->testVideTransfertArgent();
        // affectation des numéros uniques des types de compte créditeur et débiteur
        // ainsi que le numéro unique du mode de paiement du transfert
        // création d'un association impossible
        // compte débiteur : Livre / compte épargne
        // compte créditeur : Compte chèque
        // mode de paiement : retrait espèces
        $modePaiement = $transfert->getModePaiement();
        $compteDebiteur = $transfert->getCompte();
        $compteCrediteur = $transfert->getCompteCrediteur();
        $typeCompteDebiteur = $compteDebiteur->getType();
        $typeCompteCrediteur = $compteCrediteur->getType();

        $typeCompteDebiteur->setNumeroUnique(TypeCompteEnum::LIVRET_COMPTE_EPARGNE);
        $typeCompteCrediteur->setNumeroUnique(TypeCompteEnum::COMPTE_CHEQUE);
        $modePaiement->setNumeroUnique(ModePaiementEnum::RETRAIT_ESPECE);

        // test de la méthode
        $service->isTransfertArgentValide($transfert);
    }

    /**
     * @uses vérifie que la méthode lève une exception si le compte débiteur est égale
     * au compte créditeur, dans le cas ou le paramètre de levée d'exception est égale
     * à vrai (valeur par défaut)
     * @param TransfertArgentService $service
     * @depends testVideService
     * @covers TransfertArgentService::isTransfertArgentValide
     */
    public function testFailIsTransfertArgentValide4(TransfertArgentService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        $transfert = $this->testVideTransfertArgent();
        // affecation du compte créditeur égale compte débiteur
        $compteDebiteur = $transfert->getCompte();
        $transfert->setCompteCrediteur($compteDebiteur);

        // test de la méthode
        $service->isTransfertArgentValide($transfert);
    }

    /**
     * @uses vérifie si la méthode lève une exception dans le cas ou aucun compte
     * créditeur n'est trouvé dans l'objet transfert d'argent
     * @depends testVideService
     * @param TransfertArgentService $service
     * @covers TransfertArgentService::getCompteCrediteur
     */
    public function testFailGetCompte(TransfertArgentService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE);

        // création d'un nouveau tranfert d'argent
        $transfert = new TransfertArgent();

        // test d'utilisation de la méthode
        $service->getCompteCrediteur($transfert);
    }



    /**
     * @uses vérifie que la méthode retourne bien l'objet Compte
     * @depends testVideService
     * @param TransfertArgentService $service
     * @covers TransfertArgentService::getCompteCrediteur
     */
    public function testGetCompte(TransfertArgentService $service)
    {
        // création d'un nouveau transfert d'argent
        $transfert = new TransfertArgent();

        // création d'un compte créditeur
        $compteCrediteur = new Compte();
        $transfert->setCompteCrediteur($compteCrediteur);

        // test d'utilisation de la méthode
        $this->assertEquals($compteCrediteur, $service->getCompteCrediteur($transfert));
    }

    /**
     * @uses vérifie que la méthode retourne faux si le paramètre de levée d'exception
     * est égale à faux et si le transfert d'argent n'est pas valide car le compte
     * créditeur est inactif.
     * @param TransfertArgentService $service
     * @depends testVideService
     * @covers TransfertArgentService::isTransfertArgentValide
     */
    public function testIsTransfertArgentValide1(TransfertArgentService $service)
    {
        $transfert = $this->testVideTransfertArgent();
        // désactivation du compte créditeur
        $compteCrediteur = $transfert->getCompteCrediteur();
        $compteCrediteur->setActive(false);

        // test de la méthode avec paramètre d'exception désactivé (égale à faux)
        $this->assertFalse($service->isTransfertArgentValide($transfert, false));
    }

    /**
     * @uses vérifie que la méthode retourne vrai quelque soit la valeur du paramètre
     * de levée d'exception et si le transfert d'argent est valide.
     * @param TransfertArgentService $service
     * @depends testVideService
     * @covers TransfertArgentService::isTransfertArgentValide
     */
    public function testIsTransfertArgentValide2(TransfertArgentService $service)
    {
        $transfert = $this->testVideTransfertArgent();
        // affectation des numéros uniques des types de compte créditeur et débiteur
        // ainsi que le numéro unique du mode de paiement du transfert
        // création d'un association impossible
        // compte débiteur : Porte monnaie
        // compte créditeur : Compte chèque
        // mode de paiement : transfert d'argent
        $modePaiement = $transfert->getModePaiement();
        $compteDebiteur = $transfert->getCompte();
        $compteCrediteur = $transfert->getCompteCrediteur();
        $typeCompteDebiteur = $compteDebiteur->getType();
        $typeCompteCrediteur = $compteCrediteur->getType();

        $typeCompteDebiteur->setNumeroUnique(TypeCompteEnum::PORTE_MONNAIE);
        $typeCompteCrediteur->setNumeroUnique(TypeCompteEnum::COMPTE_CHEQUE);
        $modePaiement->setNumeroUnique(ModePaiementEnum::TRANSFERT_ARGENT);

        // test de la méthode avec paramètre d'exception désactivé ou activé (égale à faux)
        $this->assertTrue($service->isTransfertArgentValide($transfert));
        $this->assertTrue($service->isTransfertArgentValide($transfert, false));
    }

    /**
     * @uses vérifie que la méthode retourne faux si le compte débiteur est égale
     * au compte créditeur, dans le cas ou le paramètre de levée d'exception est égale
     * à faux
     * @param TransfertArgentService $service
     * @depends testVideService
     * @covers TransfertArgentService::isTransfertArgentValide
     */
    public function testIsTransfertArgentValide3(TransfertArgentService $service)
    {
        $transfert = $this->testVideTransfertArgent();
        // affecation du compte créditeur égale compte débiteur
        $compteDebiteur = $transfert->getCompte();
        $transfert->setCompteCrediteur($compteDebiteur);

        // test de la méthode
        $this->assertFalse($service->isTransfertArgentValide($transfert, false));
    }
}
