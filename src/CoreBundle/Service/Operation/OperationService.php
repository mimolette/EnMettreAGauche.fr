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
use CoreBundle\Service\ModePaiement\ModePaiementService;
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
class OperationService extends AbstractOperationService
{
    /** @var ModePaiementService */
    private $modePaiementService;

    /** @var OperationTicketService */
    private $operationTicketService;

    /**
     * OperationService constructor.
     * @param CompteService          $compteService
     * @param TypeCompteService      $typeCompteService
     * @param ModePaiementService    $modePaiementService
     * @param OperationTicketService $operationTicketService
     */
    public function __construct(
        CompteService $compteService,
        TypeCompteService $typeCompteService,
        ModePaiementService $modePaiementService,
        OperationTicketService $operationTicketService
    ) {
        parent::__construct($compteService, $typeCompteService);
        $this->modePaiementService = $modePaiementService;
        $this->operationTicketService = $operationTicketService;
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
        // répartition des vérifications en fonction du type de l'opération
        switch (true) {
            case $operation instanceof OperationTicket:
                // si l'opération est du type opération de ticket
                // appel du service d'opération de ticket
                $opeService = $this->operationTicketService;
                $valide = $opeService->isTicketOperationValide($operation, $throwException);
                break;
            case $operation instanceof TransfertArgent:
                // si l'opération est du type transfert d'argent
                // appel du service de transfert d'argent

                // les vérifications des opérations classique s'applique aussi à ce
                // type d'opération

                break;
            case $operation instanceof OperationCheque:
                // si l'opération est du type opération de chèque
                // appel du service d'opération de chèque

                // les vérifications des opérations classique s'applique aussi à ce
                // type d'opération
                
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
    private function isClassiqueOperationValide(AbstractOperation $operation, $throwException = true)
    {
        // validité du montant
        $valide = true;
        
        // appel aux services de compte, de type de compte et de mode de paiement
        $cService = $this->compteService;
        $tService = $this->typeCompteService;
        $mService = $this->modePaiementService;

        // récupération du compte, type de compte et du mode de paiement de l'opération
        $compte = $this->getCompte($operation);
        $typeCompte = $cService->getTypeCompte($compte);
        $modePaiementOpe = $this->getModePaiement($operation);

        // vérifie si l'opération possèdent bien un montant valide par rapport au mode de paiement
        $montantOpe = $operation->getMontant();
        $valide = $valide && $mService->isMontantOperationValide($montantOpe, $modePaiementOpe, $throwException);

        // vérifie si le compte est actif
        $valide = $valide && $cService->isCompteActif($compte, $throwException);

        // vérifie si le compte autorise ce genre de mode de paiement
        $valide = $valide && $tService->isModePaiementAutorise($modePaiementOpe, $typeCompte, $throwException);

        // aucune vérification n'as levée d'exception, l'opération est valide
        return $valide;
    }
}