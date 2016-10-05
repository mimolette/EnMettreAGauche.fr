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
//        throw new EmagException(
//            'test',
//            ExceptionCodeEnum::ACCES_FICHIER_ERREUR,
//            __METHOD__
//        );
    }

    /**
     * @return TicketUpdater
     */
    public function getTicketUpdaterService()
    {
        return $this->ticketUpdaterService;
    }
}