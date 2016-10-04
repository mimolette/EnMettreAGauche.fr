<?php

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\AjustementSolde;
use CoreBundle\Entity\Categorie;
use CoreBundle\Entity\Chequier;
use CoreBundle\Entity\Compte;
use CoreBundle\Entity\CompteCheque;
use CoreBundle\Entity\CompteSolde;
use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\Couleur;
use CoreBundle\Entity\ModePaiement;
use CoreBundle\Entity\Operation;
use CoreBundle\Entity\OperationTicket;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Enum\ModePaiementEnum;
use CoreBundle\Enum\TypeCompteEnum;
use CoreBundle\Service\Compte\SoldeUpdater;
use CoreBundle\Service\Compte\TicketUpdater;
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

        // acces au service de mise a jour des nombre de ticket de compte
        /** @var TicketUpdater $serviceTicket */
        $serviceTicket = $this->get('emag.core.compte.ticket_updater');

        // parcourt des différents type d'opérations
        foreach ($typeOperation as $modePaiementEnum => $comptes) {
            // recherche de l'objet ModePAiement grâce à l'id
            /** @var ModePaiement $ModePaiementObj */
            $modePaiementObj = $this->getReferenceWithId(
                $this->modePaiementData,
                $modePaiementEnum
            );

            // parcourt des différentes comptes
            foreach ($comptes as $compteId => $operations) {
                // recherche de l'objet Compte grâce à l'id
                /** @var Compte $compteObj */
                $compteObj = $this->getReferenceWithId($this->compteData, $compteId);
                dump($compteObj->getType());


                // parcourt des différentes opérations
                foreach ($operations as $operation) {
                    // TODO : vérifier si ce type d'opération est autorisé pour ce compte

                    // création d'une nouvelle opération en fonction du mode de paiement
                    $opeObj = ModePaiementEnum::createNewOperation($modePaiementEnum);

                    // attribution de la date
                    $dateObj = new \DateTime($operation["date"]);
                    $opeObj->setDate($dateObj);

                    // attribution du libellé
                    $opeObj->setLibelle($operation["libelle"]);

                    // attribution du mode de paiement
                    $opeObj->setModePaiement($modePaiementObj);

//                    // attribution du montant
//                    if ($opeObj instanceof OperationTicket) {
//                        $opeObj->setNbTicket($operation["nbTickets"]);
//                        // vérification et affectation du compte
//                        /** @var CompteTicket $compteObj */
//                        $serviceTicket->updateNbTicket($compteObj, $opeObj);
//                    } else {
//                        // toute les autres opérations possédent déja un montant
//                        $opeObj->setMontant($operation["montant"]);
//                    }
//
//                    // vérification et calcul du nouveau solde du compte
//                    if ($compteObj instanceof CompteSolde) {
//                        // vérification et affectation du compte
//                        /** @var CompteSolde $compteObj */
//                        $serviceSolde->updateSoldeWithOperation($compteObj, $opeObj);
//                    }
//
//                    // affectation des catégories
//                    // parcourt des différentes catégories
//                    foreach ($operation["categories"] as $catId) {
//                        // recherche de la catégorie par référence
//                        /** @var Categorie $catObj */
//                        $catObj = $this->getReferenceWithId($this->categorieData, $catId);
//
//                        $opeObj->addCatogory($catObj);
//                    }

//                    // persisitance des entités grâce à la cascade
//                    $manager->persist($opeObj);
//                    $manager->persist($compteObj);
//                    $manager->persist($catObj);
//                    $manager->flush();
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
