<?php

namespace CoreBundle\Service\ModePaiement;

use CoreBundle\Entity\ModePaiement;
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
     * @uses vérifie si le montant de l'opération est cohérent par rapport aux différentes
     * restriction du mode de paiement
     * @param float        $montant
     * @param ModePaiement $modePaiement
     * @param bool         $throwException
     * @return bool
     * @throws EmagException
     */
    public function isMontantOperationValide(
        $montant,
        ModePaiement $modePaiement,
        $throwException = true
    ) {
        // cast du montant en flotant
        $montant = (float) $montant;

        // le mode de paiement possèdent des restrictions sur le signe du montant
        if ($montant < 0) {
            // si le montant est négatif et si le mode de paiement l'autorise
            $valide = $modePaiement->getEtreNegatif();
        } elseif ($montant > 0) {
            // si le montant est positif et si le mode de paiement l'autorise
            $valide = $modePaiement->getEtrePositif();
        } else {
            // si le montant est égale à 0, le montant n'est pas valide
            $valide = false;
        }

        // si le paramètre de levée d'exception est vrai
        if (!$valide && $throwException) {
            throw new EmagException(
                "Impossible d'accéder au mode de paiement de l'opération.",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        return $valide;
    }
}