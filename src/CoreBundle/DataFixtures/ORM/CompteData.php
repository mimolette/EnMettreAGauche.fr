<?php

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\AjustementSolde;
use CoreBundle\Entity\Chequier;
use CoreBundle\Entity\CompteCheque;
use CoreBundle\Entity\CompteSolde;
use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\Couleur;
use CoreBundle\Entity\Renouvellement;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Enum\TypeCompteEnum;
use CoreBundle\Service\Compte\SoldeUpdater;
use CoreBundle\Service\Compte\TicketRenouvellement;
use CoreBundle\Service\Master\PersistentService;
use CoreBundle\Service\MiseAJourSolde;
use Doctrine\Common\Persistence\ObjectManager;
use EmagUserBundle\DataFixtures\ORM\EmagUserData;
use EmagUserBundle\Entity\EmagUser;
use MasterBundle\DataFixtures\ORM\AbstractMasterFixtures;

/**
 * CompteData class file
 *
 * PHP Version 5.6
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */

/**
 * CompteData class
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */
class CompteData extends AbstractMasterFixtures
{
    // dépendances
    /** @var CouleurData */
    private $couleurData;

    /** @var TypeCompteData */
    private $typeCompteData;

    /** @var EmagUserData */
    private $userData;

    /** @var ChequierData */
    private $chequierData;

    /**
     * CompteData constructor.
     */
    public function __construct()
    {
        $this->couleurData = new CouleurData();
        $this->typeCompteData = new TypeCompteData();
        $this->userData = new EmagUserData();
        $this->chequierData = new ChequierData();
    }

    /**
     * @return null|array of AbstractMasterFixtures
     */
    public function getDependencies()
    {
        return [
            $this->couleurData,
            $this->typeCompteData,
            $this->userData,
            $this->chequierData,
        ];
    }

    /**
     * Charge les fixtures avec l'Entity Manager
     * @param ObjectManager $manager
     * @param array $users
     * @throws \MasterBundle\Exception\EmagException
     */
    public function loadWithData(ObjectManager $manager, $users)
    {
        // acces au service de mise a jour des solde de compte
        /** @var MiseAJourSolde $serviceSolde */
        $serviceSolde = $this->get('emag.core.mise_a_jour_solde');

        // parcourt les différents utilisateurs
        foreach ($users as $userId => $comptes) {
            // recherche de la référence de l'utilisateur
            /** @var EmagUser $userObj */
            $userObj = $this->getReferenceWithId($this->userData, $userId);

            // parcourt des différents comptes
            foreach ($comptes as $compteNom => $compteData) {
                // recherche de la référence du type de compte
                /** @var TypeCompte $typeCompteObj */
                $typeCompteObj = $this->getReferenceWithId(
                    $this->typeCompteData,
                    $compteData["type"]
                );

                // création d'un Compte en fonction du type
                $compteObj = TypeCompteEnum::createNewCompte($compteData["type"]);

                $compteObj->setNom($compteNom);
                $compteObj->setNumero($compteData["numero"]);
                $compteObj->setActive($compteData["active"]);
                $compteObj->setType($typeCompteObj);
                // date de mise à jour égale la date du jour actuel
                $now = new \DateTime();
                $compteObj->setLastMiseJour($now);

                // recherche de la référence de la couleur
                /** @var Couleur $couleurObj */
                $couleurObj = $this->getReferenceWithId(
                    $this->couleurData,
                    $compteData["couleur"]
                );
                $compteObj->setCouleur($couleurObj);

                // initialisation du tableau d'éléments à persister
                $elementsAPersiter = [];

                // si le compte possèdent un solde
                if (isset($compteData["solde"])) {
                    // Création de l'ajustement du solde initial
                    $ajustement = new AjustementSolde();
                    $ajustement->setSoldeApres($compteData["solde"]);

                    // ajout de l'ajustement au compte
                    $compteObj->addAjustement($ajustement);
                    $ajustement->setCompte($compteObj);

                    // mise à jour du solde grâce au service
                    $elementsAPersiter = $serviceSolde->parAjustement($ajustement);
                }

                // si le compte possede des chequiers
                if (isset($compteData["chequiers"])) {
                    // si le compte possèdent des chèques
                    /** @var array $chequierIds */
                    if (isset($compteData["chequiers"])) {
                        // parcourt des différents chequiers
                        foreach ($compteData["chequiers"] as $chequierId) {
                            /** @var Chequier $chequier */
                            $chequier = $this->getReferenceWithId(
                                $this->chequierData,
                                $chequierId
                            );

                            // ajout du chequier au compte
                            $compteObj->addChequier($chequier);
                        }
                    }
                }

                // si le compte possèdent des tickets
                if ($compteObj instanceof CompteTicket) {
                    $compteObj->setMontantTicket($compteData["montantTicket"]);

                    // création d'un renouvellement initial
                    $renouvellement = new Renouvellement();
                    $renouvellement->setNbTickets($compteData["nbTickets"]);
                    $now = new \DateTime();
                    $renouvellement->setDate($now);
                    $renouvellement->setCompte($compteObj);

                    // ajustement du compte
                    $elementsAPersiter = $serviceSolde->parRenouvellement($renouvellement);
                }

                // ajout du compte à l'utilisateur
                $userObj->addCompte($compteObj);

                // référence par le numéro unique
                $this->makeReferenceWithId($compteData["id"], $compteObj);

                // persistance du compte et autres
                // utilisation du service de persistance
                /** @var PersistentService $persistService */
                $persistService = $this->get('emag.core.master.persistent');
                $persistService->persistMultipleObject($elementsAPersiter);
            }

            // persistance de l'utilisateur
            $manager->persist($userObj);
            $manager->flush();
        }
    }

    /**
     * @return string
     */
    protected function getUniqueId()
    {
        return "emag-compte";
    }
}
