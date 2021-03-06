<?php

namespace CoreBundle\Enum;

use CoreBundle\Entity\AbstractOperation;
use CoreBundle\Entity\OperationCheque;
use CoreBundle\Entity\OperationCourante;
use CoreBundle\Entity\OperationEspeces;
use CoreBundle\Entity\OperationTicket;
use CoreBundle\Entity\Prelevement;
use CoreBundle\Entity\RemiseCheque;
use CoreBundle\Entity\Virement;
use CoreBundle\Entity\TransfertArgent;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * ModePaiementEnum class file
 *
 * PHP Version 5.6
 *
 * @category Enumeration
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

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
    const TRANSFERT_ARGENT = 6;
    const RETRAIT_ESPECE = 7;
    const PRELEVEMENT = 8;
    const REMISE_CHEQUE = 9;

    /**
     * @param int $modePaiement
     * @return AbstractOperation
     * @throws EmagException
     */
    static public function createNewOperation($modePaiement)
    {
        // vérification $modePaiement est un entier
        $modePaiement = (int) $modePaiement;

        // revoie d'une instance d'AbstractOperation en fonction du numéro type
        switch ($modePaiement) {
            case self::TRANSFERT_ARGENT:
                return new TransfertArgent();
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
                return new TransfertArgent();
                break;
            case self::REMISE_CHEQUE:
                return new RemiseCheque();
                break;
            default:
                throw new EmagException(
                    "Ce type d' opération n'éxiste pas !!!",
                    ExceptionCodeEnum::PAS_VALEUR_ATTENDUE,
                    __METHOD__
                );
                break;
        }
    }
}