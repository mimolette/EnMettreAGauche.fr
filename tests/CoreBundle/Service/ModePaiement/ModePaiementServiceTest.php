<?php

namespace CoreBundle\Tests\Service\ModePaiement;

use CoreBundle\Entity\ModePaiement;
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
     * @uses vérifie si la méthode lève un exception dans le cas ou le montant n'est pas
     * valide et que le paramètre de levée d'exception est égale à vrai (valeur par defaut).
     * @param ModePaiementService $service
     * @depends testVideService
     * @covers ModePaiementService::isMontantOperationValide
     */
    public function testFailIsMontantOperationValide(ModePaiementService $service)
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::OPERATION_IMPOSSIBLE);

        // création d'un montant d'opération qui n'autorise pas les montant négatif
        $modePaiement = new ModePaiement();
        $modePaiement->setEtreNegatif(false);

        // test de la méthode
        $service->isMontantOperationValide(-15.26, $modePaiement);
    }

    /**
     * @uses vérifie si la méthode retourne un booléen dans le cas ou l'opération est valide
     * également dans le cas ou elle n'est pas valide est que le paramètre de levée d'exception
     * est égale à faux.
     * @param ModePaiementService $service
     * @depends testVideService
     * @covers ModePaiementService::isMontantOperationValide
     */
    public function testIsMontantOperationValide1(ModePaiementService $service)
    {
        // création d'un mode de paiement
        $modePaiement = new ModePaiement();

        // autorise négatif avec montant négatif doit retrouner vrai
        $modePaiement->setEtreNegatif(true);
        $valide = $service->isMontantOperationValide(-14.23, $modePaiement);
        $this->assertTrue($valide);

        // autorise positif avec montant positif doit retourner vrai
        $modePaiement->setEtrePositif(true);
        $valide = $service->isMontantOperationValide(149.23, $modePaiement);
        $this->assertTrue($valide);

        // n'autorise pas les négatif avec montant négatif doit retourner faux
        $modePaiement->setEtreNegatif(false);
        $valide = $service->isMontantOperationValide(-526.50, $modePaiement, false);
        $this->assertFalse($valide);

        // n'autorise pas les positif avec montant positif doit retourner faux
        $modePaiement->setEtrePositif(false);
        $valide = $service->isMontantOperationValide(2, $modePaiement, false);
        $this->assertFalse($valide);
    }

    /**
     * @uses vérifie si la méthode retourne faux dans le cas ou le montant est égale à 0 ou null
     * quelle que soit les restriction du mode de paiement dans le cas ou aucune levée
     * d'exception n'est prévue
     * @param ModePaiementService $service
     * @depends testVideService
     * @covers ModePaiementService::isMontantOperationValide
     */
    public function testIsMontantOperationValide2(ModePaiementService $service)
    {
        // création d'un mode de paiement
        $modePaiement = new ModePaiement();

        // autorise négatif et pas positif
        $modePaiement->setEtreNegatif(true);
        $modePaiement->setEtrePositif(false);
        $valide = $service->isMontantOperationValide(0.0, $modePaiement, false);
        $this->assertFalse($valide);
        $valide = $service->isMontantOperationValide(null, $modePaiement, false);
        $this->assertFalse($valide);

        // autorise négatif et positif
        $modePaiement->setEtreNegatif(true);
        $modePaiement->setEtrePositif(true);
        $valide = $service->isMontantOperationValide(0.0, $modePaiement, false);
        $this->assertFalse($valide);
        $valide = $service->isMontantOperationValide(null, $modePaiement, false);
        $this->assertFalse($valide);

        // autorise positif et pas négatif
        $modePaiement->setEtreNegatif(false);
        $modePaiement->setEtrePositif(true);
        $valide = $service->isMontantOperationValide(0.0, $modePaiement, false);
        $this->assertFalse($valide);
        $valide = $service->isMontantOperationValide(null, $modePaiement, false);
        $this->assertFalse($valide);
    }
}
