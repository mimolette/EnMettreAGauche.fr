<?php

namespace CoreBundle\Service\Operation;

use CoreBundle\Entity\Compte;
use CoreBundle\Entity\TransfertArgent;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * TransfertArgentService class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * TransfertArgentService class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class TransfertArgentService extends AbstractOperationService
{
    /**
     * @uses fonction qui vérifie si l'opération de type transfert d'argent est
     * valide. seulement les vérifications spécifique de ce type d'opération
     * @param TransfertArgent $transfert
     * @param bool            $throwException
     * @return bool
     * @throws EmagException
     */
    public function isTransfertArgentValide(TransfertArgent $transfert, $throwException = true)
    {
        // validité du transfert d'argent
        $valide = true;

        // appel au services du compte, type de compte
        $cService = $this->compteService;
        $tService = $this->typeCompteService;

        // récupération du compte créditeur, débiteur et de leur types de compte
        $compteCrediteur = $this->getCompteCrediteur($transfert);
        $compteDebiteur = $this->getCompte($transfert);
        $typeCompteCrediteur = $cService->getTypeCompte($compteCrediteur);
        $typeCompteDebiteur = $cService->getTypeCompte($compteDebiteur);
        $modePaiementTransfert = $this->getModePaiement($transfert);

        // vérifie si le compte créditeur est actif
        $valide = $valide && $cService->isCompteActif($compteCrediteur);

        // vérifie si le compte créditeur autorise ce genre de mode de paiement
        $valide = $valide && $tService->isModePaiementAutorise($modePaiementTransfert, $typeCompteCrediteur);
        
        // verifie si l'association entre type de compte débiteur, créidteur et mode
        // de paiement est valide
        $valideAssociation = $tService->isAssociationTypeCompteAutorisePourModePaiement(
            $modePaiementTransfert->getNumeroUnique(),
            $typeCompteDebiteur->getNumeroUnique(),
            $typeCompteCrediteur->getNumeroUnique(),
            $throwException
        );
        $valide = $valide && $valideAssociation;

        // aucune vérification n'as levée d'exception, le transfert d'argent est valide
        return $valide;
    }

    /**
     * @param TransfertArgent $operation
     * @return Compte
     * @throws EmagException
     */
    public function getCompteCrediteur(TransfertArgent $transfert)
    {
        $compteCrediteur = $transfert->getCompteCrediteur();
        if (null === $compteCrediteur) {
            throw new EmagException(
                "Impossible d'accéder au compte créditeur du transfert d'argent.",
                ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE,
                __METHOD__
            );
        }

        return $compteCrediteur;
    }
}