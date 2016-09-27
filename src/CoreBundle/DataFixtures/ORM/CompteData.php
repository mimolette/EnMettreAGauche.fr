<?php

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\Compte;
use CoreBundle\Entity\ModePaiement;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Enum\ModePaiementEnum;
use CoreBundle\Enum\TypeCompteEnum;
use Doctrine\Common\Persistence\ObjectManager;
use EmagUserBundle\DataFixtures\ORM\EmagUserData;
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
        [
            "nom"     => "Compte Commun",
            "solde"   => 205.45,
            "numero"  => "2786114455000",
            "type"    => TypeCompteEnum::COURANT,
            "couleur" => "rouge",
            "user"    => "Bobby",
        ],
        [
            "nom"     => "CEL",
            "solde"   => 105.20,
            "numero"  => "889955624155",
            "type"    => TypeCompteEnum::LIVRET_COMPTE_EPARGNE,
            "couleur" => "bleu",
            "user"    => "Bobby",
        ],
        [
            "nom"     => "Ticket restaurant Guillaume",
            "solde"   => 120,
            "numero"  => null,
            "type"    => TypeCompteEnum::TICKET_CHEQUE,
            "couleur" => "gris",
            "user"    => "Bobby",
        ],
        [
            "nom"     => "Porte-monnaie",
            "solde"   => 13.50,
            "numero"  => null,
            "type"    => TypeCompteEnum::PORTE_MONNAIE,
            "couleur" => "orange",
            "user"    => "Bobby",
        ],
    ];

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
    public function load(ObjectManager $manager)
    {
        // parcourt les différents mode de paiement
        foreach (self::DATA as $compteData) {
            $compteObj = new Compte();
            $compteObj->setNom($compteData["nom"]);
            $compteObj->setSolde($compteData["solde"]);
            $compteObj->setNumero($compteData["numero"]);

            // recherche de la référence de la couleur
            // recherche de la référence du type de compte
            // recherche de la référence de l'utilisateur

            // référence par le numéro unique
            $this->makeReferenceWithId($compteData["numUnique"], $compteObj);
            // persistance du compte de paiement
            $manager->persist($compteObj);
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
