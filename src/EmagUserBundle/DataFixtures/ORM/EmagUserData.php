<?php

namespace EmagUserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
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
class EmagUserData extends AbstractMasterFixtures implements ContainerAwareInterface
{
    /** liste des utilisateurs */
    const DATA = [
        [
            "username" => "Bobby",
            "password" => "test1234",
            "email"    => "bobby@test.fr",
            "active"   => true,
            "roles"    => [
                "ROLE_USER",
            ]
        ],
    ];

    /** @var  ContainerInterface */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

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
        // get FOSUserBundle Service
        $userService = $this->container->get('fos_user.user_manager');

        // parcourt des données utilisateurs
        foreach (self::DATA as $userData) {
            $userObj = $userService->createUser();
            $userObj->setUsername($userData["username"]);
            $userObj->setPlainPassword($userData["password"]);
            $userObj->setEmail($userData["email"]);
            $userObj->setEnabled($userData["active"]);
            // ajout des rôles
            foreach ($userData["roles"] as $role) {
                $userObj->addRole($role);
            }

            // référence de l'entité par le pseudo de l'utilisateur
            $this->makeReferenceWithId($userData["username"], $userObj);
            // persistance de l'entité
            $manager->persist($userObj);
            $manager->flush();
        }
    }

    /**
     * @return string
     */
    protected function getUniqueId()
    {
        return "emag-emaguser";
    }
}
