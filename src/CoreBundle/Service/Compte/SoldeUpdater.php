<?php

namespace CoreBundle\Service\Compte;

use CoreBundle\Entity\AjustementSolde;
use CoreBundle\Entity\CompteSolde;
use CoreBundle\Entity\Operation;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * SoldeUpdater class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
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
     * @throws EmagException
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

        // test si le nouveau solde est négatif et si le type de compte l'autorise
        $this->isNouveauSoldeNegatif($nouveauSolde, $compte);

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
        // récupération du solde actuel du compte
        $soldeAvant = $compte->getSolde();
        // récupération du montant de l'opération
        $montant = $operation->getMontant();

        // calcul du nouveau solde théorique pour effectuer des vérifications
        $soldeTheorique = $this->ajoutDuMontant($soldeAvant, $montant);

        // test si le nouveau solde est négatif et si le type de compte l'autorise
        $this->isNouveauSoldeNegatif($soldeTheorique, $compte);

        // ajustement du nouveau solde
        $compte->setSolde($soldeTheorique);

        // affectation de l'opération au compte
        $operation->setCompte($compte);
    }

    /**
     * @param float $ancien
     * @param float $montantAjout
     * @return float
     */
    private function ajoutDuMontant($ancien, $montantAjout)
    {
        // vérifiaction du type de variables
        $ancien = (float) $ancien;
        $montantAjout = (float) $montantAjout;

        // ajout du montant à l'ancien solde
        return $ancien + $montantAjout;
    }

    /**
     * @param float       $nouveauSolde
     * @param CompteSolde $compte
     */
    private function isNouveauSoldeNegatif($nouveauSolde, $compte)
    {
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
    }
}