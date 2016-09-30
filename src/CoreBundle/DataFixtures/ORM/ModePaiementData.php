<?php

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\ModePaiement;
use CoreBundle\Enum\ModePaiementEnum;
use Doctrine\Common\Persistence\ObjectManager;
use MasterBundle\DataFixtures\ORM\AbstractMasterFixtures;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * ModePaiementData class file
 *
 * PHP Version 5.6
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */

/**
 * ModePaiementData class
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */
class ModePaiementData extends AbstractMasterFixtures
{
    /** liste des modes de paiement */
    const DATA = [
        [
            "nom"         => "Carte bancaire",
            "etreNegatif" => true,
            "numUnique"   => ModePaiementEnum::CARTE_BANCAIRE,
        ],
        [
            "nom"         => "Espèces",
            "etreNegatif" => false,
            "numUnique"   => ModePaiementEnum::ESPECES,
        ],
        [
            "nom"         => "Chèque",
            "etreNegatif" => false,
            "numUnique"   => ModePaiementEnum::CHEQUE,
        ],
        [
            "nom"         => "Ticket restaurant",
            "etreNegatif" => false,
            "numUnique"   => ModePaiementEnum::TICKET_RESTAURANT,
        ],
        [
            "nom"         => "Virement externe",
            "etreNegatif" => true,
            "numUnique"   => ModePaiementEnum::VIREMENT_EXTERNE,
        ],
        [
            "nom"         => "Virement interne",
            "etreNegatif" => true,
            "numUnique"   => ModePaiementEnum::VIREMENT_INTERNE,
        ],
        [
            "nom"         => "Retrait espèces",
            "etreNegatif" => false,
            "numUnique"   => ModePaiementEnum::RETRAIT_ESPECE,
        ],
    ];

    /**
     * @return null|array of AbstractMasterFixtures
     */
    public function getDependencies()
    {
        return null;
    }

    /**
     * Charge les fixtures avec l'Entity Manager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // parcourt les différents mode de paiement
        foreach (self::DATA as $modeData) {
            $modeObj = new ModePaiement();
            $modeObj->setNom($modeData["nom"]);
            $modeObj->setEtreNegatif($modeData["etreNegatif"]);
            $modeObj->setNumeroUnique($modeData["numUnique"]);

            // référence par le numéro unique
            $this->makeReferenceWithId($modeData["numUnique"], $modeObj);
            // persistance du mode de paiement
            $manager->persist($modeObj);
            $manager->flush();
        }
    }

    /**
     * @return string
     */
    protected function getUniqueId()
    {
        return "emag-modepaiement";
    }
}
