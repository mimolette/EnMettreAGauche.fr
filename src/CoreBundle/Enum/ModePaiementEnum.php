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
    const VIREMENT_EXTERNE = 5;
    const VIREMENT_INTERNE = 6;
    const RETRAIT_ESPECE = 7;
}