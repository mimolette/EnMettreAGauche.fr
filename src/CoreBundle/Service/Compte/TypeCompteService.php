<?php

namespace CoreBundle\Service\Compte;

use CoreBundle\Entity\ModePaiement;
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
                "Impossible d'accéder aux modes de paiement du type de compte.",
                ExceptionCodeEnum::PAS_VALEUR_ATTENDUE,
                __METHOD__
            );
        }

        return $modes;
    }

    /**
     * @param ModePaiement $modePaiement
     * @param TypeCompte   $typeCompte
     * @param bool         $throwException
     * @return bool
     * @throws EmagException
     */
    public function isModePaiementAutorise(
        ModePaiement $modePaiement,
        TypeCompte $typeCompte,
        $throwException = true
    ) {
        // récupération des modes de paiement autorisé par le type de compte
        $modePaiementAutorises = $this->getModePaiements($typeCompte);

        // vérifie si le mode de paiement est autorisé par le type de compte
        $autorise = $modePaiementAutorises->contains($modePaiement);

        // si la méthode doit levé une exception
        if (!$autorise && $throwException) {
            // lève une exception car le mode de paiement n'est pas autorisé
            throw new EmagException(
                "Impossible d'effectuer ce genre d'opération sur ce type de compte.",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        return $autorise;
    }
}