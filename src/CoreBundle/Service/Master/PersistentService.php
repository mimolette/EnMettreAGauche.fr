<?php

namespace CoreBundle\Service\Master;

use Doctrine\ORM\EntityManager;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * PersistentService class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
/**
 * PersistentService class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class PersistentService
{
    /** @var EntityManager */
    private $entityManager;

    /**
     * AbstractPersistentService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Object $objet
     * @throws EmagException
     */
    public function persistSingleObject($objet)
    {
        try {
            // tentative de persistence de l'objet
            $this->entityManager->persist($objet);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            // levée d'un exception
            throw new EmagException(
                "Sauvegarde en base de données impossible.",
                ExceptionCodeEnum::SAVE_FAILED,
                __METHOD__,
                $exception
            );
        }
    }

    /**
     * @param array $objets
     * @throws EmagException
     */
    public function persistMultipleObject(array $objets)
    {
        try {
            // parcourt des objets
            foreach ($objets as $objet) {
                // tentative de persistence de l'objet
                $this->entityManager->persist($objet);
            }
            // fin de la transaction
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            // levée d'un exception
            throw new EmagException(
                "Sauvegarde en base de données impossible.",
                ExceptionCodeEnum::SAVE_FAILED,
                __METHOD__,
                $exception
            );
        }
    }
}