<?php

namespace EmagUserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManager;
use MasterBundle\DataFixtures\ORM\AbstractMasterFixtures;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * EmagUserData class file
 *
 * PHP Version 5.6
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */

/**
 * EmagUserData class
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */
class EmagUserData extends AbstractMasterFixtures
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
     * @param array         $users
     */
    public function loadWithData(ObjectManager $manager , $users)
    {
        // get FOSUserBundle Service
        /** @var UserManager $userService */
        $userService = $this->get('fos_user.user_manager');

        // parcourt des données utilisateurs
        foreach ($users as $username => $userData) {
            $userObj = $userService->createUser();
            $userObj->setUsername($username);
            $userObj->setPlainPassword($userData["password"]);
            $userObj->setEmail($userData["email"]);
            $userObj->setEnabled($userData["active"]);
            // ajout des rôles
            foreach ($userData["roles"] as $role) {
                $userObj->addRole($role);
            }

            // référence de l'entité par le pseudo de l'utilisateur
            $this->makeReferenceWithId($username, $userObj);
            // persistance de l'entité
            $manager->persist($userObj);
            $manager->flush();
        }
    }

    /** @return string */
    protected function getUniqueId()
    {
        return "emag-emaguser";
    }
}
