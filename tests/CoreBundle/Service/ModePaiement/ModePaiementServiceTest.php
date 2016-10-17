<?php

namespace CoreBundle\Tests\Service\ModePaiement;

use CoreBundle\Entity\ModePaiement;
use CoreBundle\Service\ModePaiement\ModePaiementService;
use CoreBundle\Tests\Service\AbstractMasterService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

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

    /**
     * @uses vérifie si la méthode retourne -15.23 dans le cas ou le mode de paiement
     * est obligatoirement négatif et si le montant de départ est 15.23
     * @param ModePaiementService $service
     * @depends testVideService
     * @covers ModePaiementService::devinerMontantParDeduction
     */
    public function testDevinerMontantParDeduction1(ModePaiementService $service)
    {
        // création du mode de paiement obligatoirement négatif
        $modePaiement = new ModePaiement();
        $modePaiement->setEtreNegatif(true);
        $modePaiement->setEtrePositif(false);

        // test de la méthode
        $this->assertEquals(-15.23, $service->devinerMontantParDeduction(15.23, $modePaiement));
    }

    /**
     * @uses vérifie si la méthode retourne 7.50 dans le cas ou le mode de paiement
     * est obligatoirement positif et si le montant de départ est 7.50
     * @param ModePaiementService $service
     * @depends testVideService
     * @covers ModePaiementService::devinerMontantParDeduction
     */
    public function testDevinerMontantParDeduction2(ModePaiementService $service)
    {
        // création du mode de paiement obligatoirement positif
        $modePaiement = new ModePaiement();
        $modePaiement->setEtreNegatif(false);
        $modePaiement->setEtrePositif(true);

        // test de la méthode
        $this->assertEquals(7.50, $service->devinerMontantParDeduction(7.50, $modePaiement));
    }

    /**
     * @uses vérifie si la méthode retourne 120.26 dans le cas ou le mode de paiement
     * est soit positif soit négatif et si le montant de départ est 120.26
     * @param ModePaiementService $service
     * @depends testVideService
     * @covers ModePaiementService::devinerMontantParDeduction
     */
    public function testDevinerMontantParDeduction3(ModePaiementService $service)
    {
        // création du mode de paiement positif ou négatif
        $modePaiement = new ModePaiement();
        $modePaiement->setEtreNegatif(true);
        $modePaiement->setEtrePositif(true);

        // test de la méthode
        $this->assertEquals(120.26, $service->devinerMontantParDeduction(120.26, $modePaiement));
    }

    /**
     * @uses vérifie si la méthode retourne -120.26 dans le cas ou le mode de paiement
     * est soit positif soit négatif et si le montant de départ est -120.26
     * @param ModePaiementService $service
     * @depends testVideService
     * @covers ModePaiementService::devinerMontantParDeduction
     */
    public function testDevinerMontantParDeduction4(ModePaiementService $service)
    {
        // création du mode de paiement positif ou négatif
        $modePaiement = new ModePaiement();
        $modePaiement->setEtreNegatif(true);
        $modePaiement->setEtrePositif(true);

        // test de la méthode
        $this->assertEquals(-120.26, $service->devinerMontantParDeduction(-120.26, $modePaiement));
    }
}
