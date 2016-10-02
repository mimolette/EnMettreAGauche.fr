<?php

namespace MasterBundle\DataFixtures\ORM;

use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Enum\RootDirectoryEnum;
use MasterBundle\Exception\EmagException;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
abstract class AbstractMasterFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    const DIR_NAME = 'DataFiles';
    const FIXTURES_DIR = '/ORM/';

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
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // récupération des données du fichier .yml correspondant
        $currentData = $this->loadDataFromYamlFile();

        // éxécution de la méthode abstraite en injectant les données
        $this->loadWithData($manager, $currentData);
    }

    /**
     * Charge les fixtures avec l'entity Manager et les données .yml
     * @param ObjectManager $manager
     * @param array         $data
     */
    abstract function loadWithData(ObjectManager $manager, $data);

    /** @return string */
    abstract protected function getUniqueId();

    /**
     * @param string $serviceAliasName
     * @return object
     * @throws EmagException
     */
    protected function get($serviceAliasName)
    {
        try {
            return $this->container->get($serviceAliasName);
        } catch (\Exception $exception) {
            throw new EmagException(
                "Impossible de charger le service ::$serviceAliasName",
                ExceptionCodeEnum::ACCES_SERVICE_ERREUR,
                __METHOD__,
                $exception
            );
        }
    }
    
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

    /**
     * @return array
     */
    protected function loadDataFromYamlFile()
    {
        // acces au service de gestion des fichiers
        $fileService = $this->container->get('emag.master.files');

        // tentative de charger un fichier en fonction du nom de la classe
        $fileName = $this->guessDataFileName();

        // récupération des données du fichier .yml en spécifiant qu'il faut chercher dans src/
        $data = $fileService->getYamlFileContents($fileName, RootDirectoryEnum::DIR_SRC);

        // retourne le tableau des données extraite du fichier .yml
        return $data;
    }

    /**
     * @return string
     */
    private function guessDataFileName()
    {
        // récupération du nom complet de la classe avec son namespace
        $classFullName = get_class($this);

        // Remplacement de FIXTURES_DIR par DIR_NAME pour conserver l'aboresence des fixtures
        $fileName = preg_replace(self::FIXTURES_DIR, self::DIR_NAME, $classFullName);

        return $fileName.'.yml';
    }
}
