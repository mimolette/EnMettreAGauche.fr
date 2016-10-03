<?php

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\Chequier;
use Doctrine\Common\Persistence\ObjectManager;
use MasterBundle\DataFixtures\ORM\AbstractMasterFixtures;

/**
 * ChequierData class file
 *
 * PHP Version 5.6
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */

/**
 * ChequierData class
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */
class ChequierData extends AbstractMasterFixtures
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
     * @param array $chequiers
     * @throws \MasterBundle\Exception\EmagException
     */
    public function loadWithData(ObjectManager $manager, $chequiers)
    {
        // parcourt des différents chéquiers
        foreach ($chequiers as $chequierId => $chequierData) {
            // création d'un nouveau chequier
            $chequierObj = new Chequier();
            $chequierObj->setNom($chequierData["nom"]);
            $chequierObj->setNumero($chequierData["numero"]);
            $chequierObj->setNbCheques($chequierData["nbCheques"]);
            $chequierObj->setActive($chequierData["active"]);

            // création d'une référence grâce à l'id
            $this->makeReferenceWithId($chequierId, $chequierObj);

            // persistance de l'objet en base de données
            $manager->persist($chequierObj);
            $manager->flush();
        }
    }

    /**
     * @return string
     */
    protected function getUniqueId()
    {
        return "emag-chequier";
    }
}
