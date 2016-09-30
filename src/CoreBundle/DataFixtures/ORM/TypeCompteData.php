<?php

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\ModePaiement;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Enum\ModePaiementEnum;
use CoreBundle\Enum\TypeCompteEnum;
use Doctrine\Common\Persistence\ObjectManager;
use MasterBundle\DataFixtures\ORM\AbstractMasterFixtures;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * TypeCompteData class file
 *
 * PHP Version 5.6
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */

/**
 * TypeCompteData class
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */
class TypeCompteData extends AbstractMasterFixtures
{
    // dépendances
    /** @var ModePaiementData */
    private $modePaiementData;

    /** liste des types de compte */
    const DATA = [
        [
            "nom"          => "Compte chèque",
            "etreNegatif"  => true,
            "numUnique"    => TypeCompteEnum::COMPTE_CHEQUE,
            "modePaiement" => [
                ModePaiementEnum::CARTE_BANCAIRE,
                ModePaiementEnum::VIREMENT_INTERNE,
                ModePaiementEnum::VIREMENT_EXTERNE,
                ModePaiementEnum::CHEQUE,
            ],
        ],
        [
            "nom"          => "Livret/Compte épargne",
            "etreNegatif"  => false,
            "numUnique"    => TypeCompteEnum::LIVRET_COMPTE_EPARGNE,
            "modePaiement" => [
                ModePaiementEnum::VIREMENT_INTERNE,
                ModePaiementEnum::VIREMENT_EXTERNE,
                ModePaiementEnum::RETRAIT_ESPECE,
            ],
        ],
        [
            "nom"          => "Ticket/Chèque",
            "etreNegatif"  => false,
            "numUnique"    => TypeCompteEnum::TICKET_CHEQUE,
            "modePaiement" => [
                ModePaiementEnum::TICKET_RESTAURANT,
            ],
        ],
        [
            "nom"          => "Porte monnaie",
            "etreNegatif"  => false,
            "numUnique"    => TypeCompteEnum::PORTE_MONNAIE,
            "modePaiement" => [
                ModePaiementEnum::ESPECES,
                ModePaiementEnum::RETRAIT_ESPECE,
            ],
        ],
    ];

    public function __construct()
    {
        $this->modePaiementData = new ModePaiementData();
    }

    /**
     * @return null|array of AbstractMasterFixtures
     */
    public function getDependencies()
    {
        return [
            $this->modePaiementData,
        ];
    }

    /**
     * Charge les fixtures avec l'Entity Manager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // parcourt les différents mode de paiement
        foreach (self::DATA as $typeData) {
            $typeObj = new TypeCompte();
            $typeObj->setNom($typeData["nom"]);
            $typeObj->setEtreNegatif($typeData["etreNegatif"]);
            $typeObj->setNumeroUnique($typeData["numUnique"]);
            // ajout des modes de paiements autorisés
            foreach ($typeData["modePaiement"] as $mode) {
                // recherche de la réference
                /** @var ModePaiement $modeObj */
                $modeObj = $this->getReferenceWithId($this->modePaiementData, $mode);
                $typeObj->addModePaiement($modeObj);
            }

            // référence par le numéro unique
            $this->makeReferenceWithId($typeData["numUnique"], $typeObj);
            // persistance du type de paiement
            $manager->persist($typeObj);
            $manager->flush();
        }
    }

    /**
     * @return string
     */
    protected function getUniqueId()
    {
        return "emag-typecompte";
    }
}
