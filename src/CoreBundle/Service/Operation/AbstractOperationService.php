<?php

namespace CoreBundle\Service\Operation;

use CoreBundle\Entity\AbstractOperation;
use CoreBundle\Entity\Compte;
use CoreBundle\Entity\ModePaiement;
use CoreBundle\Service\Compte\CompteService;
use CoreBundle\Service\Compte\TypeCompteService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * AbstractOperationService class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
/**
 * AbstractOperationService class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
abstract class AbstractOperationService
{
    /** @var CompteService */
    protected $compteService;

    /** @var TypeCompteService */
    protected $typeCompteService;

    /**
     * AbstractOperationService constructor.
     * @param CompteService     $compteService
     * @param TypeCompteService $typeCompteService
     */
    public function __construct(
        CompteService $compteService,
        TypeCompteService $typeCompteService
    ) {
        $this->compteService = $compteService;
        $this->typeCompteService = $typeCompteService;
    }

    /**
     * @param AbstractOperation $operation
     * @return Compte
     * @throws EmagException
     */
    public function getCompte(AbstractOperation $operation)
    {
        $compte = $operation->getCompte();
        if (null === $compte) {
            throw new EmagException(
                "Impossible d'accéder au compte de l'operation.",
                ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE,
                __METHOD__
            );
        }

        return $compte;
    }

    /**
     * @param AbstractOperation $operation
     * @return ModePaiement
     * @throws EmagException
     */
    public function getModePaiement(AbstractOperation $operation)
    {
        $modePaiement = $operation->getModePaiement();
        if (null === $modePaiement) {
            throw new EmagException(
                "Impossible d'accéder au mode de paiement de l'opération.",
                ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE,
                __METHOD__
            );
        }

        return $modePaiement;
    }
}