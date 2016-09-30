<?php

namespace CoreBundle\Enum;

/**
 * ExceptionCodeEnum class file
 *
 * PHP Version 5.6
 *
 * @category Enumeration
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * ExceptionCodeEnum class
 *
 * @category Enumeration
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
final class ExceptionCodeEnum
{
    /** authorisation et permission */
    const PERMISSION_DENIED = 1001;

    /** base de données */
    const NO_RESULT_FOUND = 2001;

    /** erreurs métiers */
    const PAS_VALEUR_ATTENDUE = 3001;
}