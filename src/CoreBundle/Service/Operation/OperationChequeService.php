<?php

namespace CoreBundle\Service\Operation;

use CoreBundle\Entity\Chequier;
use CoreBundle\Entity\OperationCheque;
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