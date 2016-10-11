<?php

namespace CoreBundle\Service\ModePaiement;

use CoreBundle\Entity\Compte;
use CoreBundle\Entity\AbstractOperation;
use CoreBundle\Service\Compte\CompteService;
use CoreBundle\Service\Compte\TypeCompteService;
use CoreBundle\Service\Operation\OperationService;
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
    /** @var CompteService */
    private $compteService;

    /** @var OperationService */
    private $operationService;

    /** @var TypeCompteService */
    private $typeCompteService;

    /**
     * ModePaiementService constructor.
     * @param CompteService     $compteService
     * @param OperationService  $opeService
     * @param TypeCompteService $typeCompteService
     */
    public function __construct(
        CompteService $compteService,
        OperationService $opeService,
        TypeCompteService $typeCompteService
    ) {
        $this->compteService = $compteService;
        $this->operationService = $opeService;
        $this->typeCompteService = $typeCompteService;
    }

    /**
     * @param AbstractOperation $operation
     * @param Compte $compte
     * @return bool
     */
    public function isModePaiementAutorise(
        AbstractOperation $operation,
        $throwException = true
    ) {
        // récupération du mode paiement de l'opération
        $modePaiementOperation = $this->operationService->getModePaiement($operation);
        
        // récupération du compte débiteur
        $compte = $this->operationService->getCompte($operation);

        // récupération des modes de paiement autorisé par le type de compte
        $typeCompte = $this->compteService->getTypeCompte($compte);
        $modePaiementAutorises = $this->typeCompteService->getModePaiements($typeCompte);

        // vérifie si le mode de paiement de l'opération est autorisé par le type de compte
        $autorise = $modePaiementAutorises->contains($modePaiementOperation);

        // si la méthode doit levé une exception
        if (!$autorise && $throwException) {
            // lève une exception car le mode de paiement n'est pas autorisé
            throw new EmagException(
                "Impossible d'effectuer ce genre d'opération sur le compte ::$compte",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        return $autorise;
    }
}