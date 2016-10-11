<?php

namespace CoreBundle\Service\Operation;

use CoreBundle\Entity\AjustementSolde;
use CoreBundle\Entity\Compte;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * AjustementService class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
/**
 * AjustementService class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class AjustementService
{
    /**
     * @param AjustementSolde $ajustement
     * @return Compte
     * @throws EmagException
     */
    public function getCompte(AjustementSolde $ajustement)
    {
        $compte = $ajustement->getCompte();
        if (null === $compte) {
            throw new EmagException(
                "Impossible d'accéder au compte de l'ajustement.",
                ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE,
                __METHOD__
            );
        }

        return $compte;
    }
}