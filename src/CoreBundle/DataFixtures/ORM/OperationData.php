<?php

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\Categorie;
use CoreBundle\Entity\Compte;
use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\ModePaiement;
use CoreBundle\Entity\OperationTicket;
use CoreBundle\Entity\TransfertArgent;
use CoreBundle\Enum\ModePaiementEnum;
use CoreBundle\Service\Compte\SoldeUpdater;
use CoreBundle\Service\Compte\TicketUpdater;
use CoreBundle\Service\Master\PersistentService;
use CoreBundle\Service\MiseAJourSolde;
use Doctrine\Common\Persistence\ObjectManager;
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
        /** @var MiseAJourSolde $serviceSolde */
        $serviceSolde = $this->get('emag.core.mise_a_jour_solde');

        // parcourt des différents type d'opérations
        foreach ($typeOperation as $modePaiementEnum => $comptes) {
            // recherche de l'objet ModePAiement grâce à l'id
            /** @var ModePaiement $ModePaiementObj */
            $modePaiementObj = $this->getReferenceWithId(
                $this->modePaiementData,
                $modePaiementEnum
            );

            // parcourt des différents comptes
            foreach ($comptes as $compteId => $operations) {
                // recherche de l'objet Compte grâce à l'id
                /** @var Compte $compteObj */
                $compteObj = $this->getReferenceWithId($this->compteData, $compteId);

                // parcourt des différentes opérations
                foreach ($operations as $operation) {
                    // création d'une nouvelle opération en fonction du mode de paiement
                    $opeObj = ModePaiementEnum::createNewOperation($modePaiementEnum);

                    // affectation du compte à l'opération
                    $opeObj->setCompte($compteObj);

                    // attribution de la date
                    $dateObj = new \DateTime($operation["date"]);
                    $opeObj->setDate($dateObj);

                    // attribution du libellé
                    $opeObj->setLibelle($operation["libelle"]);

                    // attribution du mode de paiement
                    $opeObj->setModePaiement($modePaiementObj);

                    dump(get_class($opeObj));

                    // attribution du montant
                    if ($opeObj instanceof OperationTicket) {
                        $opeObj->setNbTicket($operation["nbTickets"]);
                    } elseif ($opeObj instanceof TransfertArgent) {
                        // recherche du compte créditeur
                        /** @var Compte $compteCrediteur */
                        $compteCrediteur = $this->getReferenceWithId($this->compteData, $operation["compteCrediteur"]);
                        $opeObj->setCompteCrediteur($compteCrediteur);
                        // ajout du montant
                        $opeObj->setMontant($operation["montant"]);
                    } else {
                        // toute les autres opérations possédent déja un montant
                        $opeObj->setMontant($operation["montant"]);
                    }

                    // affectation des catégories
                    // parcourt des différentes catégories
                    foreach ($operation["categories"] as $catId) {
                        // recherche de la catégorie par référence
                        /** @var Categorie $catObj */
                        $catObj = $this->getReferenceWithId($this->categorieData, $catId);

                        $opeObj->addCatogory($catObj);
                    }

                    // vérification de la validité des opération plus mise à jour des soldes
                    $elementsAPersister = $serviceSolde->parOperation($opeObj);

                    // persisitance des entités grâce aux service
                    /** @var PersistentService $persistService */
                    $persistService = $this->get('emag.core.master.persistent');
                    $persistService->persistMultipleObject($elementsAPersister);
                }
            }

        }
    }

    /**
     * @return string
     */
    protected function getUniqueId()
    {
        return "emag-operation";
    }
}
