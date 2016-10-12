<?php

namespace CoreBundle\Service\Operation;

use CoreBundle\Entity\AbstractOperation;
use CoreBundle\Entity\Compte;
use CoreBundle\Entity\ModePaiement;
use CoreBundle\Entity\OperationCheque;
use CoreBundle\Entity\OperationTicket;
use CoreBundle\Entity\TransfertArgent;
use CoreBundle\Service\Compte\CompteService;
use CoreBundle\Service\Compte\TypeCompteService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * OperationService class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
/**
 * OperationService class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class OperationService
{
    /** @var CompteService */
    private $compteService;

    /** @var TypeCompteService */
    private $typeCompteService;

    /**
     * OperationService constructor.
     * @param CompteService     $compteService
     * @param TypeCompteService $typeCompteService
     */
    public function __construct(
        CompteService $compteService,
        TypeCompteService $typeCompteService
    ) {
        $this->compteService = $compteService;
        $this->typeCompteService = $typeCompteService;
    }

    /**
     * @param AbstractOperation $operation
     * @return Compte
     * @throws EmagException
     */
    public function getCompte(AbstractOperation $operation)
    {
        $compte = $operation->getCompte();
        if (null === $compte) {
            throw new EmagException(
                "Impossible d'accéder au compte de l'operation.",
                ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE,
                __METHOD__
            );
        }

        return $compte;
    }

    /**
     * @param AbstractOperation $operation
     * @return ModePaiement
     * @throws EmagException
     */
    public function getModePaiement(AbstractOperation $operation)
    {
        $modePaiement = $operation->getModePaiement();
        if (null === $modePaiement) {
            throw new EmagException(
                "Impossible d'accéder au mode de paiement de l'opération.",
                ExceptionCodeEnum::MAUVAIS_TYPE_VARIABLE,
                __METHOD__
            );
        }

        return $modePaiement;
    }

    /**
     * @uses fonction qui renvoi vrai selon si l'opération est valide, faux dans le cas
     * elle n'est pas valide, si le paramètre de levée d'exception est égale à vrai, alors
     * une exception sera levée dans le cas ou l'opération n'est pas valide.
     * @param AbstractOperation $operation
     * @param bool              $throwException
     * @return bool
     * @throws EmagException
     */
    public function isOperationValide(AbstractOperation $operation, $throwException = true)
    {
        $valide = false;
        // répartition des vérification en fonction du type de l'opération
        switch (true) {
            case $operation instanceof OperationTicket:
                // si l'opération est du type opération de ticket
                break;
            case $operation instanceof TransfertArgent:
                // si l'opération est du type transfert d'argent
                break;
            case $operation instanceof OperationCheque:
                // si l'opération est du type opération de chèque
                break;
            default:
                // tous les autres types partages les mêmes vérifications
                $valide = $this->isClassiqueOperationValide($operation, $throwException);
                break;
        }

        return $valide;
    }

    /**
     * @uses cette fonction regroupe toutes les vérifications à effectués sur les
     * opérations standards
     * @param AbstractOperation $operation
     * @param bool              $throwException
     * @return bool
     * @throws EmagException
     */
    private function isClassiqueOperationValide(AbstractOperation $operation, $throwException)
    {
        // appel aux services de compte et type de compte
        $cService = $this->compteService;
        $tService = $this->typeCompteService;

        // récupération du compte, type de compte, modes de paiement du type de compte
        // mode de paiement de l'opération
        $compte = $this->getCompte($operation);
        $typeCompte = $cService->getTypeCompte($compte);
        $modesPaiements = $tService->getModePaiements($typeCompte);
        $modePaiementOpe = $this->getModePaiement($operation);

        // vérifie si l'opération possèdent bien un montant valide par rapport au mode de paiement

        // vérifie si le compte est actif

        // vérifie si le compte autorise ce genre de mode de paiement

        return true;
    }
}