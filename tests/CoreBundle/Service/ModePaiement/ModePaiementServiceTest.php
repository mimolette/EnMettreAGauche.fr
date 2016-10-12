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
        $service->isMontantOperationValide(-14.23, $modePaiement);

        // autorise positif avec montant positif doit retourner vrai
        $modePaiement->setEtrePositif(true);
        $service->isMontantOperationValide(149.23, $modePaiement);

        // n'autorise pas les négatif avec montant négatif doit retourner faux
        $modePaiement->setEtreNegatif(false);
        $service->isMontantOperationValide(-526.50, $modePaiement, false);

        // n'autorise pas les positif avec montant positif doit retourner faux
        $modePaiement->setEtrePositif(false);
        $service->isMontantOperationValide(2, $modePaiement, false);
    }

    /**
     * @uses vérifie si la méthode retourne faux dans le cas ou le montant est égale à 0
     * quelle que soit les restriction du mode de paiement
     * @param ModePaiementService $service
     * @depends testVideService
     * @covers ModePaiementService::isMontantOperationValide
     */
    public function testIsMontantOperationValide2(ModePaiementService $service)
    {

    }
}
