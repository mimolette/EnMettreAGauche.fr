<?php

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\ModePaiement;
use Doctrine\Common\Persistence\ObjectManager;
use MasterBundle\DataFixtures\ORM\AbstractMasterFixtures;

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
     * @param array $modePaiements
     */
    public function loadWithData(ObjectManager $manager, $modePaiements)
    {
        // parcourt les différents mode de paiement
        foreach ($modePaiements as $nomType => $data) {
            $modeObj = new ModePaiement();
            $modeObj->setNom($nomType);
            $modeObj->setEtreNegatif($data["etreNegatif"]);
            $modeObj->setEtrePositif($data["etrePositif"]);
            $modeObj->setNumeroUnique($data["id"]);

            // référence par le numéro unique
            $this->makeReferenceWithId($data["id"], $modeObj);
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
