<?php

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\Categorie;
use CoreBundle\Entity\Couleur;
use Doctrine\Common\Persistence\ObjectManager;
use EmagUserBundle\DataFixtures\ORM\EmagUserData;
use EmagUserBundle\Entity\EmagUser;
use MasterBundle\DataFixtures\ORM\AbstractMasterFixtures;

/**
 * CategorieData class file
 *
 * PHP Version 5.6
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */

/**
 * CategorieData class
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */
class CategorieData extends AbstractMasterFixtures
{
    // dépendances
    /** @var CouleurData */
    private $couleurData;

    /** @var EmagUserData */
    private $userData;

    /**
     * CategorieData constructor.
     */
    public function __construct()
    {
        $this->couleurData = new CouleurData();
        $this->userData    = new EmagUserData();
    }

    /**
     * @return null|array of AbstractMasterFixtures
     */
    public function getDependencies()
    {
        return [$this->couleurData, $this->userData];
    }

    /**
     * Charge les fixtures avec l'Entity Manager
     * @param ObjectManager $manager
     * @param array $users
     */
    public function loadWithData(ObjectManager $manager, $users)
    {
        // parcourt les différents utilisateurs
        foreach ($users as $userId => $categories) {
            // recherche de la référence de l'utilisateur
            /** @var EmagUser $userObj */
            $userObj = $this->getReferenceWithId(
                $this->userData,
                $userId
            );

            // parcourt des différentes niveaux de catégories de l'utilisateur
            foreach ($categories as $catName => $catData) {
                $categorieParentObj =  new Categorie();
                if (isset($catData["active"])) {
                    $categorieParentObj->setActive(false);
                } else {
                    $categorieParentObj->setActive(true);
                }
                $categorieParentObj->setNom($catName);

                // recherche de la référence de la couleur
                /** @var Couleur $couleurObj */
                $couleurObj = $this->getReferenceWithId(
                    $this->couleurData,
                    $catData["couleur"]
                );
                $categorieParentObj->setCouleur($couleurObj);

                // ajout de la catégorie à l'utilisateur
                $userObj->addCategory($categorieParentObj);

                // création d'un identidiant unique
                $unique = $userId.'-'.$catName;

                // parcourt des niveau d'enfant
                if (isset($catData["enfants"])) {
                    $this->handleChildCategories(
                        $manager,
                        $catData["enfants"],
                        $couleurObj,
                        $unique,
                        $categorieParentObj
                    );
                }

                // référence par le nom unique
                $this->makeReferenceWithId($unique, $categorieParentObj);
                // persistance de la catégorie
                $manager->persist($categorieParentObj);
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
        return "emag-categorie";
    }

    /**
     * @param ObjectManager $manager
     * @param array         $categories
     * @param Couleur       $couleur
     * @param string        $userId
     * @param Categorie     $categorieMaster
     */
    private function handleChildCategories(
        ObjectManager $manager,
        $categories,
        Couleur $couleur,
        $userId,
        Categorie $categorieMaster
    ) {
        foreach ($categories as $catParentName => $catData) {
            $categorieObj =  new Categorie();
            if (isset($catData["active"])) {
                $categorieObj->setActive(false);
            } else {
                $categorieObj->setActive(true);
            }
            $categorieObj->setNom($catParentName);
            $categorieObj->setCouleur($couleur);

            // ajout de l'enfant à son parent
            $categorieMaster->addEnfant($categorieObj);

            // création d'un identidiant unique
            $unique = $userId.'-'.$catParentName;

            // parcourt des niveaux d'enfant
            if (isset($catData["enfants"])) {
                $this->handleChildCategories(
                    $manager,
                    $catData["enfants"],
                    $couleur,
                    $unique,
                    $categorieObj
                );
            }

            // référence par le nom unique
            $this->makeReferenceWithId($unique, $categorieObj);
            // persistance de la catégorie
            $manager->persist($categorieObj);
        }
    }
}
