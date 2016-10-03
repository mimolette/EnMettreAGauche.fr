<?php

namespace CoreBundle\Enum;

/**
 * ModePaiementEnum class file
 *
 * PHP Version 5.6
 *
 * @category Enumeration
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
use CoreBundle\Entity\Operation;
use CoreBundle\Entity\OperationCheque;
use CoreBundle\Entity\OperationCourante;
use CoreBundle\Entity\OperationEspeces;
use CoreBundle\Entity\OperationTicket;
use CoreBundle\Entity\Prelevement;
use CoreBundle\Entity\Virement;
use CoreBundle\Entity\VirementInterne;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * ModePaiementEnum class
 *
 * @category Enumeration
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
final class ModePaiementEnum
{
    const CARTE_BANCAIRE = 1;
    const ESPECES = 2;
    const CHEQUE = 3;
    const TICKET_RESTAURANT = 4;
    const VIREMENT = 5;
    const VIREMENT_INTERNE = 6;
    const RETRAIT_ESPECE = 7;
    const PRELEVEMENT = 8;

    /**
     * @param int $typeOperation
     * @return Operation
     * @throws EmagException
     */
    static function createNewOperation($typeOperation)
    {
        // vérification $typeOperation est un entier
        $typeOperation = (int) $typeOperation;

        // revoie d'une instance d'Operation en fonction du numéro type
        switch ($typeOperation) {
            case self::VIREMENT_INTERNE:
                return new VirementInterne();
                break;
            case self::TICKET_RESTAURANT:
                return new OperationTicket();
                break;
            case self::ESPECES:
                return new OperationEspeces();
                break;
            case self::CARTE_BANCAIRE:
                return new OperationCourante();
                break;
            case self::VIREMENT:
                return new Virement();
                break;
            case self::CHEQUE:
                return new OperationCheque();
                break;
            case self::PRELEVEMENT:
                return new Prelevement();
                break;
            case self::RETRAIT_ESPECE:
                return new OperationEspeces();
                break;
            default:
                throw new EmagException(
                    "Ce type d' opération n'éxiste pas !!!",
                    __METHOD__,
                    ExceptionCodeEnum::PAS_VALEUR_ATTENDUE
                );
                break;
        }
    }
}