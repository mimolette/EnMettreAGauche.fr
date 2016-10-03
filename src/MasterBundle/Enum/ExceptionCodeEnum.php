<?php

namespace MasterBundle\Enum;

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
    const VALEURS_INCOHERENTES = 3002;

    /** erreurs serveur */
    const FICHIER_INTROUVABLE = 4001;
    const DOSSIER_INTROUVABLE = 4002;
    const ACCES_SERVICE_ERREUR = 4011;
    const ACCES_FICHIER_ERREUR = 4021;
}