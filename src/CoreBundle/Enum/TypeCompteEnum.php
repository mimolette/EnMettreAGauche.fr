<?php

namespace CoreBundle\Enum;

use CoreBundle\Entity\Compte;
use CoreBundle\Entity\CompteTicket;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * TypeCompteEnum class file
 *
 * PHP Version 5.6
 *
 * @category Enumeration
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * TypeCompteEnum class
 *
 * @category Enumeration
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
final class TypeCompteEnum
{
    const LIVRET_COMPTE_EPARGNE = 1;
    const PORTE_MONNAIE = 2;
    const COMPTE_CHEQUE = 3;
    const TICKET_CHEQUE = 4;

    /**
     * @param int $typeCompte
     * @return Compte
     * @throws EmagException
     */
    static function createNewCompte($typeCompte)
    {
        // vérification $typeCompte est un entier
        $typeCompte = (int) $typeCompte;
        
        // revoie d'une instance de Compte en fonction du numéro type
        switch ($typeCompte) {
            case self::COMPTE_CHEQUE:
                return new Compte();
                break;
            case self::LIVRET_COMPTE_EPARGNE:
                return new Compte();
                break;
            case self::PORTE_MONNAIE:
                return new Compte();
                break;
            case self::TICKET_CHEQUE:
                return new CompteTicket();
                break;
            default:
                throw new EmagException(
                    "Ce type de compte n'éxiste pas !!!",
                    ExceptionCodeEnum::PAS_VALEUR_ATTENDUE,
                    __METHOD__
                );
                break;
        }
    }
}