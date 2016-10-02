<?php

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\ModePaiement;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Enum\ModePaiementEnum;
use CoreBundle\Enum\TypeCompteEnum;
use Doctrine\Common\Persistence\ObjectManager;
use MasterBundle\DataFixtures\ORM\AbstractMasterFixtures;

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
     * @param array $typeComptes
     */
    public function loadWithData(ObjectManager $manager, $typeComptes)
    {
        // parcourt les différents mode de paiement
        foreach ($typeComptes as $nomType => $typeData) {
            $typeObj = new TypeCompte();
            $typeObj->setNom($nomType);
            $typeObj->setEtreNegatif($typeData["etreNegatif"]);
            $typeObj->setNumeroUnique($typeData["id"]);
            // ajout des modes de paiements autorisés
            foreach ($typeData["modePaiements"] as $mode) {
                // recherche de la réference
                /** @var ModePaiement $modeObj */
                $modeObj = $this->getReferenceWithId($this->modePaiementData, $mode);
                $typeObj->addModePaiement($modeObj);
            }

            // référence par le numéro unique
            $this->makeReferenceWithId($typeData["id"], $typeObj);
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
