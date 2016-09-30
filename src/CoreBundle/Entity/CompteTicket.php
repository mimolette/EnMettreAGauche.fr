<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompteTicket class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * CompteTicket class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 * 
 * @ORM\Table(name="emag_compte_ticket")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\CompteTicketRepository")
 */
class CompteTicket extends Compte
{
    /**
     * @var int
     *
     * @ORM\Column(name="nb_tickets", type="integer")
     */
    private $nbTickets;

    /**
     * @var float
     *
     * @ORM\Column(name="montant_ticket", type="float")
     */
    private $montantTicket;

    /**
     * Set nbTickets
     *
     * @param integer $nbTickets
     *
     * @return CompteTicket
     */
    public function setNbTickets($nbTickets)
    {
        $this->nbTickets = $nbTickets;

        return $this;
    }

    /**
     * Get nbTickets
     *
     * @return int
     */
    public function getNbTickets()
    {
        return $this->nbTickets;
    }

    /**
     * Set montantTicket
     *
     * @param float $montantTicket
     *
     * @return CompteTicket
     */
    public function setMontantTicket($montantTicket)
    {
        $this->montantTicket = $montantTicket;

        return $this;
    }

    /**
     * Get montantTicket
     *
     * @return float
     */
    public function getMontantTicket()
    {
        return $this->montantTicket;
    }
}
