<?php

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\CompteSolde;
use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\Couleur;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Enum\TypeCompteEnum;
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

    /**
     * CompteData constructor.
     */
    public function __construct()
    {
        $this->couleurData = new CouleurData();
        $this->typeCompteData = new TypeCompteData();
        $this->userData = new EmagUserData();
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

                // recherche de la référence de la couleur
                /** @var Couleur $couleurObj */
                $couleurObj = $this->getReferenceWithId(
                    $this->couleurData,
                    $compteData["couleur"]
                );
                $compteObj->setCouleur($couleurObj);

                // si le compte possèdent un solde
                if ($compteObj instanceof CompteSolde) {
                    $compteObj->setSolde($compteData["solde"]);
                }

                // si le compte possèdent des tickets
                if ($compteObj instanceof CompteTicket) {
                    $compteObj->setNbTickets($compteData["nbTickets"]);
                    $compteObj->setMontantTicket($compteData["montantTicket"]);
                }

                // ajout du compte à l'utilisateur
                $userObj->addCompte($compteObj);

                // référence par le numéro unique
                $this->makeReferenceWithId($compteData["id"], $compteObj);

                // persistance du compte
                $manager->persist($compteObj);
                $manager->flush();
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
