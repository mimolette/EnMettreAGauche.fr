<?php

namespace CoreBundle\Service\ModePaiement;

use CoreBundle\Entity\Compte;
use CoreBundle\Entity\Operation;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * ModePaiementService class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
/**
 * ModePaiementService class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class ModePaiementService
{
    /**
     * @param Operation $operation
     * @param Compte $compte
     * @return bool
     */
    public function isModePaiementAutorise(Operation $operation, Compte $compte)
    {
        // récupération du mode paiement de l'opération
        $modePaiementOperation = $operation->getModePaiement();
        if (null === $modePaiementOperation) {
            throw new EmagException(
                "Impossible de vérifie le mode de paiement de l'opération.",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        // récupération des mode de paiement autorisé par le type de compte
        $typeCompte = $compte->getType();
        if (null === $typeCompte) {
            throw new EmagException(
                "Impossible de trouver le type de compte du compte ::$compte.",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }
        $modePaiementAutorises = $typeCompte->getModePaiements();
        if (0 === $modePaiementAutorises->count()) {
            throw new EmagException(
                "Impossible d'effectué l'opération car ce type de compte n'autorise aucun mode de paiement.",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        // vérifie si le mode de paiement de l'opération est autorisé par le type de compte
        $autorise = $modePaiementAutorises->contains($modePaiementOperation);

        return $autorise;
    }
}