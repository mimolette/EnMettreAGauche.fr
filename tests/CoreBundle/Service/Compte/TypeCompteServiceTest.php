<?php

namespace CoreBundle\Tests\Service\Compte;

use CoreBundle\Entity\Compte;
use CoreBundle\Entity\ModePaiement;
use CoreBundle\Entity\OperationCourante;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Service\Compte\TypeCompteService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;
use MasterBundle\Test\AbstractMasterService;

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
}
