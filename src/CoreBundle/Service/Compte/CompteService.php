<?php

namespace CoreBundle\Service\Compte;

use CoreBundle\Entity\Compte;
use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\TypeCompte;
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
class CompteService
{
    /**
     * @param Compte $compte
     * @param bool   $throwException
     * @return bool
     * @throws EmagException
     */
    public function isCompteActif(Compte $compte, $throwException = true)
    {
        $actif = $compte->isActive();
        if (!$actif && $throwException) {
            // lève une exception si le compte n'est pas actif
            throw new EmagException(
                "Impossible d'effectuer l'opération sur le compte ::$compte car celui-ci est inactif",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        return $actif;
    }

    /**
     * @param float  $nouveauSolde
     * @param Compte $compte
     * @throws EmagException
     */
    public function setNouveauSolde($nouveauSolde, Compte $compte)
    {
        // vérification du type de nouveauSolde
        $nouveauSolde = (float) $nouveauSolde;

        // tentative d'affecter le nouveau solde au compte
        if ($nouveauSolde < 0) {
            // test si le type de compte autorise cette valeur de solde
            $typeCompte = $this->getTypeCompte($compte);
            $etreNegatif = $typeCompte->getEtreNegatif();
            if (!$etreNegatif) {
                throw new EmagException(
                    "Impossible d'effectuer l'opération du compte ::$compte, le solde ne peut pas être négatif.",
                    ExceptionCodeEnum::VALEURS_INCOHERENTES,
                    __METHOD__
                );
            }
        }

        // mise à jour du solde du compte
        $compte->setSolde($nouveauSolde);
    }

    /**
     * @param int          $nbTicket
     * @param CompteTicket $compte
     * @throws EmagException
     */
    public function addNbTicket($nbTicket, CompteTicket $compte)
    {
        // vérification que le nombre de ticket est au moins égale à 1
        $nbTicket = (int) $nbTicket;
        if ($nbTicket < 1) {
            throw new EmagException(
                "Impossible d'effectuer l'opération de renouvellement de ticket du compte ::$compte.",
                ExceptionCodeEnum::VALEURS_INCOHERENTES,
                __METHOD__
            );
        }

        // mise à jour du nombre de tickets du compte
        $nbTicketAvant = $compte->getNbTickets();
        $compte->setNbTickets($nbTicketAvant+$nbTicket);

        // mise à jour du solde du compte
        $solde = $compte->getNbTickets()*$compte->getMontantTicket();
        $this->setNouveauSolde($solde, $compte);
    }
    
    public function isAutoriseAuxAjustements(Compte $compte, $throwException = true)
    {
        // vérification si le type du compte autorise bien les ajustements
        $typeCompte = $this->getTypeCompte($compte);
        $autorise = $typeCompte->isAutoriseAjustements();

        // si le compte n'est pas autorisé
        if (!$autorise && $throwException) {
            // lève une exception si le compte n'est pas autorisé aux ajustements
            throw new EmagException(
                "Le compte ::$compte n'autorise pas les ajustements.",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        return $autorise;
    }

    /**
     * @param Compte $compte
     * @return TypeCompte
     * @throws EmagException
     */
    public function getTypeCompte(Compte $compte)
    {
        $typeComtpe = $compte->getType();
        if (null === $typeComtpe) {
            throw new EmagException(
                "Impossible d'accéder au type du compte ::$compte.",
                ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE,
                __METHOD__
            );
        }

        return $typeComtpe;
    }
}