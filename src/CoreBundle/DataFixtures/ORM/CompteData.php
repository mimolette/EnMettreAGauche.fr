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
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

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

    /** liste des comptes */
    const DATA = [
        // Les comptes courants
        TypeCompteEnum::COMPTE_CHEQUE => [
            [
                "id"      => "compte-commun-bobby",
                "nom"     => "Compte Commun",
                "solde"   => 205.45,
                "numero"  => "2786114455000",
                "couleur" => "rouge",
                "user"    => "Bobby",
                "active"  => true,
            ],
        ],
        // Les PEL, CEL, livret A, ...
        TypeCompteEnum::LIVRET_COMPTE_EPARGNE => [
            [
                "id"      => "cel-bobby",
                "nom"     => "CEL",
                "solde"   => 105.20,
                "numero"  => "889955624155",
                "couleur" => "bleu",
                "user"    => "Bobby",
                "active"  => true,
            ],
        ],
        // les tickets restaurant ou chèques vacances, ...
        TypeCompteEnum::TICKET_CHEQUE => [
            [
                "id"            => "t-resto-guillaume-bobby",
                "nom"           => "Ticket restaurant Guillaume",
                "nbTickets"     => 19,
                "montantTicket" => 8.50,
                "numero"        => null,
                "couleur"       => "gris",
                "user"          => "Bobby",
                "active"        => true,
            ],
        ],
        // les portes monnaies
        TypeCompteEnum::PORTE_MONNAIE => [
            [
                "id"      => "porte-monnaie-bobby",
                "nom"     => "Porte-monnaie",
                "solde"   => 13.50,
                "numero"  => null,
                "couleur" => "orange",
                "user"    => "Bobby",
                "active"  => true,
            ],
        ],
    ];

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
     */
    public function loadWithData(ObjectManager $manager, $data)
    {
        // parcourt les différents types de compte
        foreach (self::DATA as $typeCompteNum => $comptes) {
            // recherche de la référence du type de compte
            /** @var TypeCompte $typeCompteObj */
            $typeCompteObj = $this->getReferenceWithId(
                $this->typeCompteData,
                $typeCompteNum
            );

            // parcourt des différents comptes
            foreach ($comptes as $compteData) {
                // création d'un Compte en fonction du type
                $compteObj = TypeCompteEnum::createNewCompte($typeCompteNum);

                $compteObj->setNom($compteData["nom"]);
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

                // recherche de la référence de l'utilisateur
                /** @var EmagUser $userObj */
                $userObj = $this->getReferenceWithId(
                    $this->userData,
                    $compteData["user"]
                );
                $userObj->addCompte($compteObj);

                // si le compte possèdent un solde
                if ($compteObj instanceof CompteSolde) {
                    $compteObj->setSolde($compteData["solde"]);
                }

                // si le compte possèdent des tickets
                if ($compteObj instanceof CompteTicket) {
                    $compteObj->setNbTickets($compteData["nbTickets"]);
                    $compteObj->setMontantTicket($compteData["montantTicket"]);
                }

                // référence par le numéro unique
                $this->makeReferenceWithId($compteData["id"], $compteObj);
                // persistance du compte de paiement
                $manager->persist($compteObj);
                $manager->flush();

                // persistance de l'utilisateur
                $manager->persist($userObj);
                $manager->flush();
            }
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
