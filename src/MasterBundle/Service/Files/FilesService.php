<?php

namespace MasterBundle\Service\Files;

use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Enum\RootDirectoryEnum;
use MasterBundle\Exception\EmagException;
use Symfony\Component\Yaml\Yaml;

/**
 * FilesService class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */

/**
 * FilesService class
 *
 * @category Service
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */
final class FilesService
{
    /** @var string */
    const SEPARATEUR_FICHIER_REGEX = '/[\/\\\]/';

    /** @var string */
    private $rootDir;

    /**
     * FilesService constructor.
     * @param string $rootDir
     */
    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * @param string $fileName
     * @param string $rootDir
     * @return array
     * @throws EmagException
     */
    public function getYamlFileContents($fileName, $rootDir)
    {
        // gestion du nom de fichier en fonction du système d'exploitation
        $fileName = $this->getTruePath($fileName, $rootDir);

        // vérification si le fichier éxiste bien
        if (!$this->isFileExist($fileName)) {
            throw new EmagException(
                "Le fichier ::$fileName est introuvable.",
                ExceptionCodeEnum::FICHIER_INTROUVABLE,
                __METHOD__
            );
        }

        // récupération des données du fichier avec le parser YAML
        $data = Yaml::parse(file_get_contents($fileName));

        // retourne le tableau des données extraite du fichier .yml
        return $data;
    }

    /**
     * @param string $fileName
     * @return bool
     */
    private function isFileExist($fileName)
    {
        return file_exists($fileName);
    }

    /**
     * @param string $olderFileName
     * @param string $rootDir
     * @return string
     * @throws EmagException
     */
    private function getTruePath($olderFileName, $rootDir)
    {
        // remplacement des / ou \ par la constante de séparteur de fichier local
        $newFileName = preg_replace(self::SEPARATEUR_FICHIER_REGEX, DIRECTORY_SEPARATOR, $olderFileName);

        // retourne le chemin complet du fichier en fonction du répertoire racine
        return $this->getRootDir($rootDir).$newFileName;
    }

    /**
     * @param int $rootDir
     * @return string
     */
    public function getRootDir($rootDir)
    {
        switch ($rootDir) {
            case RootDirectoryEnum::DIR_APP:
                $rootDir = $this->rootDir;
                break;
            case RootDirectoryEnum::DIR_BIN:
                $rootDir = preg_replace('/(app)$/', RootDirectoryEnum::DIR_BIN, $this->rootDir);
                break;
            case RootDirectoryEnum::DIR_SRC:
                $rootDir = preg_replace('/(app)$/', RootDirectoryEnum::DIR_SRC, $this->rootDir);
                break;
            case RootDirectoryEnum::DIR_TESTS:
                $rootDir = preg_replace('/(app)$/', RootDirectoryEnum::DIR_TESTS, $this->rootDir);
                break;
            case RootDirectoryEnum::DIR_VAR:
                $rootDir = preg_replace('/(app)$/', RootDirectoryEnum::DIR_VAR, $this->rootDir);
                break;
            case RootDirectoryEnum::DIR_WEB:
                $rootDir = preg_replace('/(app)$/', RootDirectoryEnum::DIR_WEB, $this->rootDir);
                break;
            default:
                throw new EmagException(
                    "Répertoire de fichier introuvable",
                    ExceptionCodeEnum::DOSSIER_INTROUVABLE,
                    __METHOD__
                );
                break;
        }

        return $rootDir.DIRECTORY_SEPARATOR;
    }
}
