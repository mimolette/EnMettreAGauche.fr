<?php

namespace CoreBundle\Service\Operation;

use CoreBundle\Entity\Chequier;
use CoreBundle\Entity\OperationCheque;
use CoreBundle\Service\Compte\ChequierService;
use CoreBundle\Service\Compte\CompteService;
use CoreBundle\Service\Compte\TypeCompteService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * OperationChequeService class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * OperationChequeService class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class OperationChequeService extends AbstractOperationService
{
    /** @var ChequierService */
    private $chequierService;

    /**
     * OperationChequeService constructor.
     * @param CompteService     $compteService
     * @param TypeCompteService $typeCompteService
     * @param ChequierService   $chequierService
     */
    public function __construct(
        CompteService $compteService,
        TypeCompteService $typeCompteService,
        ChequierService $chequierService
    ) {
        parent::__construct($compteService, $typeCompteService);
        $this->chequierService = $chequierService;
    }

    /**
     * @uses fonction qui vérifie si l'opération de chèque est valide, a savoir si elle est bien
     * lié à un chequier valide.
     * @param OperationCheque $operation
     * @param bool            $throwException
     * @return bool
     * @throws EmagException
     */
    public function isOperationChequeValide(OperationCheque $operation, $throwException = true)
    {
        // validité de l'opération
        $valide = true;

        // appel au services du chequier
        $cService = $this->chequierService;

        // récupération du chequier de l'opération
        $chequier = $this->getChequier($operation);

        // vérification si le chequier est actif
        $valide = $valide && $cService->isChequierActif($chequier, $throwException);

        // vérification si le nombre de chèque du chequier est valide pour un opération
        $valide = $valide && $cService->isNbChequeValidePourOperation($chequier, $throwException);

        // aucune vérification n'as levée d'exception, l'opération est valide
        return $valide;
    }

    /**
     * @param OperationCheque $operation
     * @return Chequier
     * @throws EmagException
     */
    public function getChequier(OperationCheque $operation)
    {
        $chequier = $operation->getChequier();
        if (null === $chequier) {
            throw new EmagException(
                "Impossible d'accéder au chequier de l'opération chèque.",
                ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE,
                __METHOD__
            );
        }

        return $chequier;
    }
}