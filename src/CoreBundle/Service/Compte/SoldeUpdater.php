<?php

namespace CoreBundle\Service\Compte;

use CoreBundle\Entity\AjustementSolde;
use CoreBundle\Entity\Compte;
use CoreBundle\Entity\AbstractOperation;
use CoreBundle\Entity\TransfertArgent;
use CoreBundle\Enum\TypeCompteEnum;
use CoreBundle\Service\ModePaiement\ModePaiementService;
use CoreBundle\Service\Operation\TransfertArgentService;
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
    /** @var ModePaiementService */
    private $modePaiementService;

    /** @var TransfertArgentService */
    private $transfertArgentService;

    /**
     * SoldeUpdater constructor.
     * @param ModePaiementService    $modePaiementService
     * @param TransfertArgentService $transfertService
     */
    public function __construct(
        ModePaiementService $modePaiementService,
        TransfertArgentService $transfertService
    ) {
        $this->modePaiementService = $modePaiementService;
        $this->transfertArgentService = $transfertService;
    }

    /**
     * @return ModePaiementService
     */
    public function getModePaiementService()
    {
        return $this->modePaiementService;
    }

    /**
     * @return TransfertArgentService
     */
    public function getTransfertArgentService()
    {
        return $this->transfertArgentService;
    }

    /**
     * @param AjustementSolde $ajustement
     * @throws EmagException
     */
    public function updateSoldeWithAjustement(AjustementSolde $ajustement)
    {
        // vérification si l'ajustements est bien relié à un compte
        $compte = $ajustement->getCompte();
        if (null === $compte) {
            throw new EmagException(
                "Impossible d'effectuer l'ajustement car il n'est rattaché à auncun compte.",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        // vérification si le compte autorise les ajustements
        $typeCompte = $compte->getType();
        if (!TypeCompteEnum::autoriseAuxAjustements($typeCompte->getNumeroUnique())) {
            throw new EmagException(
                "Impossible d'effectuer l'ajustement car le compte ::$compte n'autorise pas les ajustements.",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        // vérification si le compte est inactif
        $this->throwExceptionIfNotActif($compte);

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
     * @param AbstractOperation $operation
     * @throws EmagException
     */
    public function updateSoldeWithOperation(AbstractOperation $operation)
    {
        // acces aux services
        $paiementService = $this->getModePaiementService();
        $transfertArgentService = $this->getTransfertArgentService();

        // vérification si l'opération est bien rattaché à un compte
        $compte = $operation->getCompte();
        if (null === $compte) {
            throw new EmagException(
                "Impossible d'effectuer l'opération car elle n'est rattachée à auncun compte.",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        // vérification si le compte est inactif
        if (!$compte->isActive()) {
            throw new EmagException(
                "Impossible d'effectuer cette opération sur le compte ::$compte car celui-ci est inactif",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        // vérification si le compte autorise ce genre d'opération
        $autorise = $paiementService->isModePaiementAutorise($operation, $compte);
        if (!$autorise) {
            throw new EmagException(
                "Impossible d'effectuer cette opération sur le compte ::$compte ne l'autorise pas",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        // vérification si l'opération est un transfert d'argent, alors passage du relai au service concerné.
        if ($operation instanceof TransfertArgent) {
            $transfertArgentService->updateComptesSoldes($operation);
        } else {
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
        }
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
     * @param float  $nouveauSolde
     * @param Compte $compte
     * @throws EmagException
     */
    private function isNouveauSoldeNegatif($nouveauSolde, Compte $compte)
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

    private function throwExceptionIfNotActif(Compte $compte)
    {
        // vérifie si le compte est actif
        if (!$compte->isActive()) {
            throw new EmagException(
                "Impossible d'effectuer cette opération sur le compte ::$compte car celui-ci est inactif",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }
    }
}