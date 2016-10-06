<?php

namespace CoreBundle\Service\Compte;

use CoreBundle\Entity\CompteTicket;
use CoreBundle\Entity\Renouvellement;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * TicketRenouvellement class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
/**
 * TicketRenouvellement class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class TicketRenouvellement
{
    /** @var TicketUpdater */
    private $ticketUpdaterService;

    /**
     * TicketRenouvellement constructor.
     * @param TicketUpdater $ticketService
     */
    public function __construct(TicketUpdater $ticketService)
    {
        $this->ticketUpdaterService = $ticketService;
    }

    /**
     * @param CompteTicket $compte
     * @param Renouvellement $renouvellement
     */
    public function renouvellerCompte(CompteTicket $compte, Renouvellement $renouvellement)
    {
        // vérification si le compte est inactif
        if (!$compte->isActive()) {
            throw new EmagException(
                "Impossible d'effectuer cette opération sur le compte ::$compte car celui-ci est inactif",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        // vérification si le renouvellement possède bien un nombre de ticket
        if (!is_numeric($renouvellement->getNbTickets())) {
            throw new EmagException(
                "Impossible d'effectuer cette opération car le nombre de ticket à ajouter n'est pas valide",
                ExceptionCodeEnum::PAS_VALEUR_ATTENDUE,
                __METHOD__
            );
        }

        // récupération nb de ticket avant
        $nbTicketCompteAvant = $compte->getNbTickets();

        // calcul du nouveau solde de ticket théorique
        $nbTicketTheorique = $nbTicketCompteAvant + $renouvellement->getNbTickets();

        // si le nombre de ticket après est inférieur à celui d'avant
        if ($nbTicketCompteAvant >= $nbTicketTheorique) {
            throw new EmagException(
                "Impossible d'effectuer cette opération car le nombre de ticket doit évoluer positivement",
                ExceptionCodeEnum::VALEURS_INCOHERENTES,
                __METHOD__
            );
        }

        // affectation du nouveau nombre de ticket
        $compte->setNbTickets($nbTicketTheorique);
    }

    /**
     * @return TicketUpdater
     */
    public function getTicketUpdaterService()
    {
        return $this->ticketUpdaterService;
    }
}