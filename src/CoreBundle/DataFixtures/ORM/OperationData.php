<?php

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\AjustementSolde;
use CoreBundle\Entity\Chequier;
use CoreBundle\Entity\CompteCheque;
use CoreBundle\Entity\CompteSolde;
use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\Couleur;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Enum\TypeCompteEnum;
use CoreBundle\Service\Compte\SoldeUpdater;
use Doctrine\Common\Persistence\ObjectManager;
use EmagUserBundle\DataFixtures\ORM\EmagUserData;
use EmagUserBundle\Entity\EmagUser;
use MasterBundle\DataFixtures\ORM\AbstractMasterFixtures;

/**
 * OperationData class file
 *
 * PHP Version 5.6
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */

/**
 * OperationData class
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */
class OperationData extends AbstractMasterFixtures
{
    // dépendances
    /** @var CompteData() */
    private $compteData;

    /** @var ModePaiementData */
    private $modePaiementData;

    /** @var CategorieData */
    private $categorieData;

    /** @var ChequierData */
    private $chequierData;

    /**
     * CompteData constructor.
     */
    public function __construct()
    {
        $this->compteData = new CompteData();
        $this->modePaiementData = new ModePaiementData();
        $this->categorieData = new CategorieData();
        $this->chequierData = new ChequierData();
    }

    /**
     * @return null|array of AbstractMasterFixtures
     */
    public function getDependencies()
    {
        return [
            $this->compteData,
            $this->modePaiementData,
            $this->categorieData,
            $this->chequierData,
        ];
    }

    /**
     * Charge les fixtures avec l'Entity Manager
     * @param ObjectManager $manager
     * @param array $typeOperation
     * @throws \MasterBundle\Exception\EmagException
     */
    public function loadWithData(ObjectManager $manager, $typeOperation)
    {
        // acces au service de mise a jour des solde de compte
        /** @var SoldeUpdater $serviceSolde */
        $serviceSolde = $this->get('emag.core.compte.solde_updater');

    }

    /**
     * @return string
     */
    protected function getUniqueId()
    {
        return "emag-operation";
    }
}
