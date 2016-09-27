<?php

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\Couleur;
use Doctrine\Common\Persistence\ObjectManager;
use MasterBundle\DataFixtures\ORM\AbstractMasterFixtures;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * CouleurData class file
 *
 * PHP Version 5.6
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */

/**
 * CouleurData class
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */
class CouleurData extends AbstractMasterFixtures
{
    /** liste des couleurs */
    const DATA = [
        [
            "nom"      => "rose",
            "codeHexa" => "#f9aaee",
        ],
        [
            "nom"      => "jaune",
            "codeHexa" => "#edf992",
        ],
        [
            "nom"      => "vert",
            "codeHexa" => "#b4fdb7",
        ],
        [
            "nom"      => "bleu",
            "codeHexa" => "#b4f9fd",
        ],
        [
            "nom"      => "bleu foncé",
            "codeHexa" => "#b4b6fd",
        ],
        [
            "nom"      => "rouge",
            "codeHexa" => "#fba0a0",
        ],
        [
            "nom"      => "violet",
            "codeHexa" => "#dda0fb",
        ],
        [
            "nom"      => "orange",
            "codeHexa" => "#ffc78b",
        ],
        [
            "nom"      => "maron",
            "codeHexa" => "#c57a4d",
        ],
        [
            "nom"      => "gris",
            "codeHexa" => "#dcdcdc",
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
        foreach (self::DATA as $couleurData) {
            $couleurObj = new Couleur();
            $couleurObj->setCodeHexa($couleurData["codeHexa"]);

            // référence par le nom unique
            $this->makeReferenceWithId($couleurData["nom"], $couleurObj);
            // persistance de la couleur
            $manager->persist($couleurObj);
            $manager->flush();
        }
    }

    /**
     * @return string
     */
    protected function getUniqueId()
    {
        return "emag-couleur";
    }
}
