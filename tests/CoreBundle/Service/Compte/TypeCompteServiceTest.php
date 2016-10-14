<?php

namespace CoreBundle\Tests\Service\Compte;

use CoreBundle\Entity\Compte;
use CoreBundle\Entity\ModePaiement;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Enum\ModePaiementEnum;
use CoreBundle\Enum\TypeCompteEnum;
use CoreBundle\Service\Compte\TypeCompteService;
use CoreBundle\Tests\Service\AbstractMasterService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * TypeCompteServiceTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * TypeCompteServiceTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class TypeCompteServiceTest extends AbstractMasterService
{
    /**
     * @return TypeCompteService
     */
    public function testVideService()
    {
        $this->setUp();

        return $this->get('emag.core.type_compte');
    }

    /**
     * @uses vérifie si la méthode lève une exception dans le cas ou aucun mode de
     * paiements n'est trouvé dans l'objet TypeCompte
     * @depends testVideService
     * @param TypeCompteService $service
     * @covers TypeCompteService::getModePaiements
     */
    public function testFailGetModePaiements(TypeCompteService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::PAS_VALEUR_ATTENDUE);

        // création d'un nouveau type de compte sans mode de paiement
        $typeCompte = new TypeCompte();

        // test d'utilisation de la méthode
        $service->getModePaiements($typeCompte);
    }

    /**
     * @uses verifie que la méthode lève une exception dans le cas ou le mode de paiement
     * n'est pas autorisé pour ce type de compte à la condition que le parmètre de levée
     * d'exception soit égale à vrai (par défaut)
     * @param TypeCompteService $service
     * @depends testVideService
     * @covers TypeCompteService::isModePaiementAutorise
     */
    public function testFailIsModePaiementAutorise(TypeCompteService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création d'un compte avec un type de compte
        $compte = new Compte();
        $typeCompte = new TypeCompte();
        $compte->setType($typeCompte);

        // ajout de deux modes de paiement autorisés pour le type de compte
        $modePaiement1 = new ModePaiement();
        $modePaiement2 = new ModePaiement();
        $typeCompte->addModePaiement($modePaiement1);
        $typeCompte->addModePaiement($modePaiement2);

        // création d'un autre mode de paiement
        $modePaiement3 = new ModePaiement();

        //test de la méthode
        $service->isModePaiementAutorise($modePaiement3, $typeCompte);
    }

    /**
     * @uses verifie que la méthode lève une exception dans le cas ou l'association
     * entre compte débiteur, créditeur et mode de paiement n'est pas autorisé, seulement
     * dans le cas ou le paramètre de levée d'exception est égale à vrai (valeur par défaut)
     * @param TypeCompteService $service
     * @depends testVideService
     * @covers TypeCompteService::isAssociationTypeCompteAutorisePourModePaiement
     */
    public function testFailIsAssociationTypeCompteAutorisePourModePaiement(
        TypeCompteService $service
    ) {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // une compte cheque ne doit pas pouvoir être créditeur dans une opération de
        // type retrait d'espèce avec un autre compte chèque comme débiteur
        $service->isAssociationTypeCompteAutorisePourModePaiement(
            ModePaiementEnum::RETRAIT_ESPECE,
            TypeCompteEnum::COMPTE_CHEQUE,
            TypeCompteEnum::COMPTE_CHEQUE
        );
    }

    /**
     * @uses vérifie que la méthode retourne bien un tableau de ModePaiement
     * @depends testVideService
     * @param TypeCompteService $service
     * @covers TypeCompteService::getModePaiements
     */
    public function testGetModePaiements(TypeCompteService $service)
    {
        // création d'un nouveau type de compte
        $typeCompte = new TypeCompte();

        // création de deux modes de paiements
        $modePaiement1 = new ModePaiement();
        $modePaiement2 = new ModePaiement();

        // ajout des modes de paiement au type de compte
        $typeCompte->addModePaiement($modePaiement1);
        $typeCompte->addModePaiement($modePaiement2);

        // récupération du tableau de mode de paiements
        $modes = $typeCompte->getModePaiements();

        // test d'utilisation de la méthode
        $this->assertEquals($modes, $service->getModePaiements($typeCompte));
    }

    /**
     * @uses vérifie que la méthode retourne un booléen dans le cas ou le mode de paiement
     * est autorisé sur ce type de compte (avec ou sans la levée d'exception) ainsi que
     * si le mode de paiement n'est pas autorisé.
     * @param TypeCompteService $service
     * @depends testVideService
     * @covers TypeCompteService::isModePaiementAutorise
     */
    public function testIsModePaiementAutorise(TypeCompteService $service)
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

        // création d'un autre mode de paiement
        $modePaiement3 = new ModePaiement();

        // test de la méthode qui doit retourner faux
        $this->assertFalse($service->isModePaiementAutorise($modePaiement3, $typeCompte, false));

        // test de la méthode qui doit retourner vrai
        $this->assertTrue($service->isModePaiementAutorise($modePaiement1, $typeCompte));
        $this->assertTrue($service->isModePaiementAutorise($modePaiement1, $typeCompte, true));
    }

    /**
     * @uses verifie que la méthode retourne un booléen qui valide ou invalide
     * l'association entre compte débiteur, créditeur et mode de paiement.
     * C'est méthode doit retourner vrai si lassociation est valide quelque soit
     * la valeur du paramètre de levée d'exception et faux si l'association n'est
     * pas valide à condition que le paramètre de levée d'exception soit égale à
     * faux.
     * @param TypeCompteService $service
     * @depends testVideService
     * @covers TypeCompteService::isAssociationTypeCompteAutorisePourModePaiement
     */
    public function testIsAssociationTypeCompteAutorisePourModePaiement(
        TypeCompteService $service
    ) {
        // une compte cheque ne doit pas pouvoir être créditeur dans une opération de
        // type retrait d'espèce avec un autre compte chèque comme débiteur
        // valeur attendue : faux
        $this->assertFalse($service->isAssociationTypeCompteAutorisePourModePaiement(
            ModePaiementEnum::RETRAIT_ESPECE,
            TypeCompteEnum::COMPTE_CHEQUE,
            TypeCompteEnum::COMPTE_CHEQUE,
            false
        ));

        // une compte cheque doit pouvoir être créditeur dans une opération de
        // type transfert d'argent avec un porte monnaie comme débiteur
        // valeur attendue : vrai (avec ou sans paramètre de levée d'exception)
        $this->assertTrue($service->isAssociationTypeCompteAutorisePourModePaiement(
            ModePaiementEnum::TRANSFERT_ARGENT,
            TypeCompteEnum::PORTE_MONNAIE,
            TypeCompteEnum::COMPTE_CHEQUE
        ));
        $this->assertTrue($service->isAssociationTypeCompteAutorisePourModePaiement(
            ModePaiementEnum::TRANSFERT_ARGENT,
            TypeCompteEnum::PORTE_MONNAIE,
            TypeCompteEnum::COMPTE_CHEQUE,
            false
        ));
    }
}
