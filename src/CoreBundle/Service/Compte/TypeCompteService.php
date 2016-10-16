<?php

namespace CoreBundle\Service\Compte;

use CoreBundle\Entity\ModePaiement;
use CoreBundle\Entity\TypeCompte;
use CoreBundle\Enum\ModePaiementEnum;
use CoreBundle\Enum\TypeCompteEnum;
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
    /** @param array */
    const ASSOCIATIONS_AUTORISES = [
        TypeCompteEnum::COMPTE_CHEQUE => [
            ModePaiementEnum::RETRAIT_ESPECE => [
                TypeCompteEnum::PORTE_MONNAIE,
                TypeCompteEnum::TIRELIRE,
            ],
            ModePaiementEnum::TRANSFERT_ARGENT => [
                TypeCompteEnum::COMPTE_CHEQUE,
                TypeCompteEnum::LIVRET_COMPTE_EPARGNE,
            ],
        ],
        TypeCompteEnum::LIVRET_COMPTE_EPARGNE => [
            ModePaiementEnum::RETRAIT_ESPECE => [
                TypeCompteEnum::PORTE_MONNAIE,
                TypeCompteEnum::TIRELIRE,
            ],
            ModePaiementEnum::TRANSFERT_ARGENT => [
                TypeCompteEnum::COMPTE_CHEQUE,
                TypeCompteEnum::LIVRET_COMPTE_EPARGNE,
            ],
        ],
        TypeCompteEnum::PORTE_MONNAIE => [
            ModePaiementEnum::TRANSFERT_ARGENT => [
                TypeCompteEnum::PORTE_MONNAIE,
                TypeCompteEnum::COMPTE_CHEQUE,
                TypeCompteEnum::LIVRET_COMPTE_EPARGNE,
                TypeCompteEnum::TIRELIRE,
            ],
        ],
        TypeCompteEnum::TIRELIRE => [
            ModePaiementEnum::TRANSFERT_ARGENT => [
                TypeCompteEnum::PORTE_MONNAIE,
                TypeCompteEnum::COMPTE_CHEQUE,
                TypeCompteEnum::LIVRET_COMPTE_EPARGNE,
                TypeCompteEnum::TIRELIRE,
            ],
        ],
    ];

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

    /**
     * @uses vérifie si l'association entre deux compte avec un mode de paiement
     * particuliers est autorisée
     * @param int  $modePaiementEnum
     * @param int  $typeCompteDebiteurEnum
     * @param int  $typeCompteCrediteurEnum
     * @param bool $throwException
     * @return bool
     * @throws EmagException
     */
    public function isAssociationTypeCompteAutorisePourModePaiement(
        $modePaiementEnum,
        $typeCompteDebiteurEnum,
        $typeCompteCrediteurEnum,
        $throwException = true
    ) {
        // validité de l'association
        $valide = true;

        // cast des enum en entier
        $modePaiementEnum = (int) $modePaiementEnum;
        $typeCompteDebiteurEnum = (int) $typeCompteDebiteurEnum;
        $typeCompteCrediteurEnum = (int) $typeCompteCrediteurEnum;

        // on recherche parmi les association autorisée
        $associations = self::ASSOCIATIONS_AUTORISES;

        $valide = $valide && isset($associations[$typeCompteDebiteurEnum]);
        // si le type de compte n'autorise pas ce genre d'opération
        if (!$valide && $throwException) {
            throw new EmagException(
                "Ce type de compte n'autorise pas ce genre d'opération.",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        if (!$valide) {
            return $valide;
        }

        // on continue les tests pour vérifier si le mode de paiement
        // autorise la présence d'un compte créditeur
        $compteDebiteur = $associations[$typeCompteDebiteurEnum];

        $valide = $valide && isset($compteDebiteur[$modePaiementEnum]);
        // si le mode de paiement n'autorise ne nécessite pas un compte créditeur
        if (!$valide && $throwException) {
            throw new EmagException(
                "Ce mode de paiement ne nécessite pas de compte créditeur.",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        if (!$valide) {
            return $valide;
        }

        // on continue les tests pour vérifier si le type de compte créditeur est
        // autorise pour ce type de compte débiteur et ce mode de paiement
        $modePaiement = $compteDebiteur[$modePaiementEnum];

        $valide = $valide && in_array($typeCompteCrediteurEnum, $modePaiement);
        // si le mode de paiement n'autorise pas ce genre de type de compte
        if (!$valide && $throwException) {
            throw new EmagException(
                "Ce mode de paiement n'autorise pas ce type de compte à être créditeur.",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        return $valide;
    }
}