<?php

namespace MasterBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * AbstractMasterFixtures class file
 *
 * PHP Version 5.6
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */

/**
 * AbstractMasterFixtures class
 *
 * @category Fixtures
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */
abstract class AbstractMasterFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Retourne l'ordre de chargement de la fixture
     * @return int
     */
    public function getOrder()
    {
        $dependencies = $this->getDependencies();
        $order = 0;
        if ($dependencies) {
            foreach ($dependencies as $dep) {
                $depOrder = $dep->getOrder();
                $order = $depOrder > $order ? $depOrder : $order;
            }
            $order++;
        }

        return $order;
    }

    /**
     * @return null|array of AbstractMasterFixtures
     */
    abstract public function getDependencies();

    /**
     * Charge les fixtures avec l'Entity Manager
     * @param ObjectManager $manager
     */
    abstract public function load(ObjectManager $manager);

    /**
     * @return string
     */
    abstract protected function getUniqueId();

    /**
     * @param int    $id
     * @param object $obj
     */
    protected function makeReferenceWithId($id, $obj)
    {
        $idRef = $this->getUniqueId().'-'.$id;
        $this->addReference($idRef, $obj);
    }

    /**
     * @param AbstractMasterFixtures $dataClass
     * @param int                    $id
     * @return object
     */
    protected function getReferenceWithId(AbstractMasterFixtures $dataClass, $id)
    {
        $idRef = $dataClass->getUniqueId().'-'.$id;

        return $this->getReference($idRef);
    }
}
