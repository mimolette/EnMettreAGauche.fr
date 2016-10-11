<?php

namespace CoreBundle\Service\Operation;

use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\Renouvellement;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * RenouvellementService class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
/**
 * RenouvellementService class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class RenouvellementService
{
    /**
     * @param Renouvellement $renouvellement
     * @return CompteTicket
     * @throws EmagException
     */
    public function getCompte(Renouvellement $renouvellement)
    {
        $compte = $renouvellement->getCompte();
        if (null === $compte) {
            throw new EmagException(
                "Impossible d'accéder au compte du renouvellement de tickets.",
                ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE,
                __METHOD__
            );
        }

        return $compte;
    }
}