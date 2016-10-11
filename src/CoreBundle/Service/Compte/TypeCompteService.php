<?php

namespace CoreBundle\Service\Compte;

use CoreBundle\Entity\TypeCompte;
use Doctrine\Common\Collections\ArrayCollection;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * TypeCompteService class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
/**
 * TypeCompteService class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class TypeCompteService
{
    /**
     * @param TypeCompte $typeCompte
     * @return ArrayCollection
     * @throws EmagException
     */
    public function getModePaiements(TypeCompte $typeCompte)
    {
        $modes = $typeCompte->getModePaiements();
        if (0 === $modes->count()) {
            throw new EmagException(
                "Impossible d'accéder au mode de paiements du type de compte.",
                ExceptionCodeEnum::PAS_VALEUR_ATTENDUE,
                __METHOD__
            );
        }

        return $modes;
    }
}