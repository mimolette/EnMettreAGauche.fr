<?php

namespace CoreBundle\Service\Compte;

use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\OperationTicket;
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
    /**
     * @param CompteTicket    $compte
     * @param OperationTicket $operation
     * @throws EmagException
     */
    public function updateNbTicket(CompteTicket $compte, OperationTicket $operation)
    {
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

        // modification du nombre de ticket du compte
        $compte->setNbTickets($nbTicketTheorique);

        // affectation du compte à l'opération
        $operation->setCompte($compte);
    }
}