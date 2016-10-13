<?php

namespace CoreBundle\Service\Compte;

use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * CompteTicketService class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * CompteTicketService class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class CompteTicketService
{
    /**
     * @param float $montant
     * @param bool  $throwException
     * @return bool
     * @throws EmagException
     */
    public function isMontantTicketValide($montant, $throwException = true)
    {
        // cast du montant en flotant
        $montant = (float) $montant;

        // le montant doit forcément être positif
        $valide = $montant > 0;
        // si une exception doit être levée en cas d'invalidité
        if (!$valide && $throwException) {
            throw new EmagException(
                "Le montant des ticket du compte n'est pas valide.",
                ExceptionCodeEnum::VALEURS_INCOHERENTES,
                __METHOD__
            );
        }

        return $valide;
    }
}