<?php

namespace CoreBundle\Service\Compte;

use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\OperationTicket;
use CoreBundle\Service\ModePaiement\ModePaiementService;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * TicketUpdater class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * TicketUpdater class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class TicketUpdater
{
    /** @var ModePaiementService */
    private $modePaiementService;

    /**
     * SoldeUpdater constructor.
     * @param ModePaiementService $modePaiementService
     */
    public function __construct(ModePaiementService $modePaiementService)
    {
        $this->modePaiementService = $modePaiementService;
    }

    /**
     * @return ModePaiementService
     */
    public function getModePaiementService()
    {
        return $this->modePaiementService;
    }

    /**
     * @param OperationTicket $operation
     * @throws EmagException
     */
    public function updateNbTicket(OperationTicket $operation)
    {
        // acces au service
        $paiementService = $this->getModePaiementService();

        // vérification si l'opération est bien rattaché à un compte
        $compte = $operation->getCompte();
        if (null === $compte) {
            throw new EmagException(
                "Impossible d'effectuer l'ajustement de ticket car aucun compte n'a été trouvé.",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        // vérification si le compte est bien un compte ticket
        if (!$compte instanceof CompteTicket) {
            throw new EmagException(
                "Impossible d'effectuer l'ajustement de ticket car le type de compte ne correspond pas.",
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
        
        // vérification si le compte est inactif
        if (!$compte->isActive()) {
            throw new EmagException(
                "Impossible d'effectuer cette opération sur le compte ::$compte car celui-ci est inactif",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        // récupération du nombre de ticket du compte avant
        $nbTicketCompteAvant = (int) $compte->getNbTickets();

        // récupération du nombre de ticket de l'opération
        $nbTicketOperation = (int) $operation->getNbTicket();

        // calcul du nouveau nombre de ticket théorique
        $nbTicketTheorique = $nbTicketCompteAvant - $nbTicketOperation;

        // si le nombre de ticket théorique est négatif
        if ($nbTicketTheorique < 0) {
            throw new EmagException(
                "Impossible d'effectué l'opération, le compte ::$compte ne posséde pas le nombre de ticket requis",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }
        
        // affectation du nouveau montant de l'opération
        $operation->calculMontant($compte->getMontantTicket());

        // modification du nombre de ticket du compte
        $compte->setNbTickets($nbTicketTheorique);
    }
}