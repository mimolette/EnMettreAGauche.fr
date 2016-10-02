<?php

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\Couleur;
use Doctrine\Common\Persistence\ObjectManager;
use MasterBundle\DataFixtures\ORM\AbstractMasterFixtures;

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
     * @param array $couleurs
     */
    public function loadWithData(ObjectManager $manager, $couleurs)
    {
        // parcourt les différents mode de paiement
        foreach ($couleurs["couleurs"] as $couleurData) {
            $couleurObj = new Couleur();
            $couleurObj->setCodeHexa($couleurData[1]);

            // référence par le nom unique
            $this->makeReferenceWithId($couleurData[0], $couleurObj);
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
