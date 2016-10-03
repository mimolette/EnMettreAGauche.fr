<?php

namespace CoreBundle\Service\Compte;

/**
 * SoldeUpdater class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
use CoreBundle\Entity\AjustementSolde;
use CoreBundle\Entity\CompteSolde;
use CoreBundle\Entity\Operation;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * SoldeUpdater class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class SoldeUpdater
{
    /**
     * @param CompteSolde     $compte
     * @param AjustementSolde $ajustement
     */
    public function updateSoldeWithAjustement(
        CompteSolde $compte,
        AjustementSolde $ajustement
    ) {
        // récupération de solde actuel du compte
        $soldeAvant = $compte->getSolde();
        
        // vérification si la valeur de l'ajustement avant = solde avant
        $valeurAvantAjustement = $ajustement->getSoldeAvant();
        if ($soldeAvant !== $valeurAvantAjustement) {
            throw new EmagException(
                "Impossible d'effectuer l'ajustement du compte ::$compte, les valeurs de soldes initiales sont incohérentes.",
                ExceptionCodeEnum::VALEURS_INCOHERENTES,
                __METHOD__
            );
        }
        
        // récupération du nouveau solde
        $nouveauSolde = $ajustement->getSoldeApres();
        if (null === $nouveauSolde) {
            throw new EmagException(
                "Impossible d'effectuer l'ajustement du compte ::$compte, aucune nouvelle valeur pour le nouveau solde.",
                ExceptionCodeEnum::PAS_VALEUR_ATTENDUE,
                __METHOD__
            );
        }

        // si le nouveau solde est négatif
        if ($nouveauSolde < 0) {
            // test si le type de compte autorise cette valeur de solde
            $etreNegatif = $compte->getType()->getEtreNegatif();
            if (!$etreNegatif) {
                throw new EmagException(
                    "Impossible d'effectuer l'ajustement du compte ::$compte, le nouveau solde ne peut pas être négatif.",
                    ExceptionCodeEnum::VALEURS_INCOHERENTES,
                    __METHOD__
                );
            }
        }

        // ajustement du nouveau solde
        $compte->setSolde($nouveauSolde);
    }

    /**
     * @param CompteSolde $compte
     * @param Operation   $operation
     */
    public function updateSoldeWithOperation(
        CompteSolde $compte,
        Operation $operation
    ) {

    }
}