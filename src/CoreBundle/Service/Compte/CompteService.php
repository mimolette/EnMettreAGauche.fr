<?php

namespace CoreBundle\Service\Compte;

use CoreBundle\Entity\AbstractOperation;
use CoreBundle\Entity\Compte;
use CoreBundle\Entity\TransfertArgent;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * CompteService class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
/**
 * CompteService class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class CompteService extends AbstractCompteService
{
    /**
     * @uses tente de mettre à jour le solde d'un ou plusieurs comptes suite à une
     * opération (valide)
     * @param AbstractOperation $operation
     * @return array des comptes à persiter
     */
    public function miseAjourSoldeParOperation(AbstractOperation $operation)
    {
        // utilisation d'une méthode de mise à jour en fonction du type d'opération
        if ($operation instanceof TransfertArgent) {
            // utilisation de la méthode spécifique aux transfert d'argent
            $elementsAPersister = $this->majSoldeParTransfertArgent($operation);
        } else {
            // utilisation de la méthode classique
            $elementsAPersister = $this->majSoldeClassique($operation);
        }

        // retourne les éléments à persiter dans un tableau
        return $elementsAPersister;
    }

    /**
     * @uses vérifie que la mise à jour du solde est possible sans l'effectuer
     * @param float  $nouveauSolde
     * @param Compte $compte
     * @return bool
     */
    public function isMajSoldePossible($nouveauSolde, Compte $compte)
    {
        // validité de la mise à jour
        $valide = true;
        // vérification du type de nouveauSolde
        $nouveauSolde = (float) $nouveauSolde;

        // si le nouveau solde est négatif
        if ($nouveauSolde < 0) {
            // test si le type de compte autorise cette valeur de solde
            $typeCompte = $this->getTypeCompte($compte);
            $etreNegatif = $typeCompte->getEtreNegatif();
            if (!$etreNegatif) {
                $valide = false;
            }
        }

        return $valide;
    }

    /**
     * @uses tente de mettre à jour le solde du compte débiteur d'une opération
     * @param AbstractOperation $operation
     * @throws EmagException
     * @return array contenant le compte débiteur
     */
    private function majSoldeClassique(AbstractOperation $operation)
    {
        // récupération du compte débiteur et de son solde
        $compte = $operation->getCompte();
        $soldeAvant = $compte->getSolde();

        // récupération du montant de l'opération
        $montant = $operation->getMontant();

        // calcul du nouveau solde théorique
        $soldeTheorique = $soldeAvant + $montant;

        // test si la mise à jour su solde du compte est possible
        $possible = $this->isMajSoldePossible($soldeTheorique, $compte);

        // lève une exception si la mise à jour est impossible
        if (!$possible) {
            throw new EmagException(
                "Impossible de mettre à jour le solde du compte ::$compte.",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        // mise à jour du solde
        $this->setNouveauSolde($soldeTheorique, $compte);

        // retour le tableau du compte plus opération
        return [$operation, $compte];
    }

    /**
     * @uses tente de mettre à jour le solde du compte créditeur et débiteur
     * suite à un transfert d'argent
     * @param TransfertArgent $operation
     * @throws EmagException
     * @return array contenant le compte débiteur et créditeur plus opération
     */
    private function majSoldeParTransfertArgent(TransfertArgent $operation)
    {
        // récupération du compte débiteur et de son solde
        $compteDebiteur = $operation->getCompte();
        $soldeAvantDebiteur = $compteDebiteur->getSolde();
        // récupération du compte créditeur et de son solde
        $compteCrediteur = $operation->getCompteCrediteur();
        $soldeAvantCrediteur = $compteCrediteur->getSolde();

        // récupération du montant de l'opération
        $montant = $operation->getMontant();

        // calcul du nouveau solde théorique du compte débiteur
        $soldeTheoriqueDebiteur = $soldeAvantDebiteur - $montant;
        // calcul du nouveau solde théorique du compte créditeur
        $soldeTheoriqueCrediteur = $soldeAvantCrediteur + $montant;

        // test si la mise à jour su solde du compte débiteur est possible
        $possibleDebiteur = $this->isMajSoldePossible($soldeTheoriqueDebiteur, $compteDebiteur);
        // test si la mise à jour su solde du compte créditeur est possible
        $possibleCrediteur = $this->isMajSoldePossible($soldeTheoriqueCrediteur, $compteCrediteur);

        // lève une exception si l'une des deux mise à jour mise à jour est impossible
        if (!$possibleDebiteur || !$possibleCrediteur) {
            throw new EmagException(
                "Impossible de mettre à jour les soldes des comptes ::$compteDebiteur et ::$compteCrediteur.",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        // mise à jour des soldes
        $this->setNouveauSolde($soldeTheoriqueDebiteur, $compteDebiteur);
        $this->setNouveauSolde($soldeTheoriqueCrediteur, $compteCrediteur);

        // retour le tableau de comptes plus opération
        return [$operation, $compteDebiteur, $compteCrediteur];
    }
}