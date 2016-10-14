<?php

namespace CoreBundle\Tests\Success\Service\Operation;

use CoreBundle\Entity\Compte;
use CoreBundle\Entity\ModePaiement;
use CoreBundle\Entity\TransfertArgent;
use CoreBundle\Entity\TypeCompte;
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

        // création du compte créditeur
        $compteCrediteur = new Compte();
        // création du type de compte
        $typeCompteCrediteur = new TypeCompte();
        $typeCompteCrediteur->addModePaiement($modePaiement1);
        $typeCompteCrediteur->addModePaiement($modePaiement2);
        $typeCompteCrediteur->addModePaiement($modePaiement3);
        $typeCompteCrediteur->addModePaiement($modePaiement4);
        $compteCrediteur->setType($typeCompteCrediteur);

        // création d'un transfert d'argent
        $transfert = new TransfertArgent();
        $transfert->setCompte($compteDebiteur);
        $transfert->setCompteCrediteur($compteCrediteur);

        return $transfert;
    }

    /**
     * @uses vérifie que la méthode lève un exception si le paramètre de levée d'exception
     * est égale à vrai (valeur par défaut) dans le cas ou le comtpte créditeur est incatif.
     * @param TransfertArgentService $service
     * @depends testVideService
     * @depends testVideTransfertArgent
     * @covers TransfertArgentService::isTransfertArgentValide
     */
    public function testFailIsTransfertArgentValide1(TransfertArgentService $service, TransfertArgent $transfert)
    {
        // TODO : réaliser le test
    }

    /**
     * @uses vérifie que la méthode lève un exception si le paramètre de levée d'exception
     * est égale à vrai (valeur par défaut) dans le cas le type du compte créditeur
     * n'autorise pas le mode de paiement du transfert d'argent
     * @param TransfertArgentService $service
     * @depends testVideService
     * @depends testVideTransfertArgent
     * @covers TransfertArgentService::isTransfertArgentValide
     */
    public function testFailIsTransfertArgentValide2(TransfertArgentService $service, TransfertArgent $transfert)
    {
        // TODO : réaliser le test
    }

    /**
     * @uses vérifie que la méthode lève un exception si le paramètre de levée d'exception
     * est égale à vrai (valeur par défaut) dans le cas ou l'association entre comtpe
     * type du compte débiteur, créditeur et mode de paiement du transfert d'argent
     * n'est pas valide.
     * @param TransfertArgentService $service
     * @depends testVideService
     * @depends testVideTransfertArgent
     * @covers TransfertArgentService::isTransfertArgentValide
     */
    public function testFailIsTransfertArgentValide3(TransfertArgentService $service, TransfertArgent $transfert)
    {
        // TODO : réaliser le test
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
     * @covers TransfertArgentService::getCompte
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
     * @depends testVideTransfertArgent
     * @covers TransfertArgentService::isTransfertArgentValide
     */
    public function testIsTransfertArgentValide1(TransfertArgentService $service, TransfertArgent $transfert)
    {
        // TODO : réaliser le test
    }

    /**
     * @uses vérifie que la méthode retourne vrai quelque soit la valeur du paramètre
     * de levée d'exception et si le transfert d'argent est valide.
     * @param TransfertArgentService $service
     * @depends testVideService
     * @depends testVideTransfertArgent
     * @covers TransfertArgentService::isTransfertArgentValide
     */
    public function testIsTransfertArgentValide2(TransfertArgentService $service, TransfertArgent $transfert)
    {
        // TODO : réaliser le test
    }
}
