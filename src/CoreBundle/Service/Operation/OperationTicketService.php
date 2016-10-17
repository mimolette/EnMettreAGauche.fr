<?php

namespace CoreBundle\Service\Operation;

use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\OperationTicket;
use CoreBundle\Service\Compte\CompteService;
use CoreBundle\Service\Compte\CompteTicketService;
use CoreBundle\Service\Compte\TypeCompteService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * OperationTicketService class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
/**
 * OperationTicketService class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class OperationTicketService extends AbstractOperationService
{
    /** @var CompteTicketService */
    private $compteTicketService;

    /**
     * OperationTicketService constructor.
     * @param CompteService       $compteService
     * @param TypeCompteService   $typeCompteService
     * @param CompteTicketService $compteTicketService
     */
    public function __construct(
        CompteService $compteService,
        TypeCompteService $typeCompteService,
        CompteTicketService $compteTicketService
    ) {
        parent::__construct($compteService, $typeCompteService);
        $this->compteTicketService = $compteTicketService;
    }

    /**
     * @uses cette fonction regroupe toutes les vérifications à effectuer sur les
     * opérations de type ticket
     * @param OperationTicket $operation
     * @param bool            $throwException
     * @return bool
     * @throws EmagException
     */
    public function isTicketOperationValide(OperationTicket $operation, $throwException = true)
    {
        // validité de l'opération
        $valide = true;

        // appel aux services de compte, de type de compte
        $cService = $this->compteService;
        $tService = $this->typeCompteService;

        // récupération du compte, type de compte et du mode de paiement de l'opération
        $compte = $this->getCompte($operation);
        $typeCompte = $cService->getTypeCompte($compte);
        $modePaiementOpe = $this->getModePaiement($operation);

        // vérifie si le compte est actif
        $valide = $valide && $cService->isCompteActif($compte, $throwException);

        // vérifie si le compte autorise ce genre de mode de paiement
        $valide = $valide && $tService->isModePaiementAutorise($modePaiementOpe, $typeCompte, $throwException);
        
        // mise à jour du montant de l'opération et vérification du nombre de tickets
        // et du montant des tickets du compte
        $valide = $valide && $this->guessMontant($operation, $throwException);

        // aucune vérification n'as levée d'exception
        return $valide;
    }

    /**
     * @uses cette fonction met à jour le montant de l'opération de ticket en fonction
     * du nombre de ticket et d'un montant des ticket du compte
     * @param OperationTicket $operation
     * @param bool            $throwException
     * @return bool
     * @throws EmagException
     */
    public function guessMontant(OperationTicket $operation, $throwException = true)
    {
        // validité du montant
        $valide = true;

        // appel du service de compte ticket
        $cService = $this->compteTicketService;

        // récupération du compte de l'opération
        /** @var CompteTicket $compte */
        $compte = $this->getCompte($operation);
        $montantTicket = $compte->getMontantTicket();
        
        // si le montant du ticket n'est pas valide
        $valide = $valide && $cService->isMontantTicketValide($montantTicket, $throwException);

        // vérifie si l'opération possèdent bien un nombre de ticket valide
        $nbTicket = $operation->getNbTicket();
        $valide = $valide && $this->isNbTicketValide($nbTicket, $throwException);

        // calcul du montant de l'opération
        $montant = -($nbTicket*$montantTicket);
        $operation->setMontant($montant);

        return $valide;
    }

    /**
     * @uses cette fonction vérifie que le nombre de ticket est valide, a savoir un entier
     * positif
     * @param int  $nbTicket
     * @param bool $throwException
     * @return bool
     * @throws EmagException
     */
    public function isNbTicketValide($nbTicket, $throwException = true)
    {
        // le nombre de ticket doit être un entier positif
        $nbTicket = (int) $nbTicket;

        $valide = $nbTicket > 0;
        // si une exception doit être levée
        if (!$valide && $throwException) {
            throw new EmagException(
                "Le nombre de ticket de l'opération n'est pas valide.",
                ExceptionCodeEnum::VALEURS_INCOHERENTES,
                __METHOD__
            );
        }

        return $valide;
    }
}