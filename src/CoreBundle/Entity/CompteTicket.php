<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
    private $nbTickets = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="montant_ticket", type="float")
     */
    private $montantTicket;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CoreBundle\Entity\Renouvellement", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="emag_ticket_renouvellement",
     *     joinColumns={@ORM\JoinColumn(name="compte_id", referencedColumnName="id_compte")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="renouvellement_id", referencedColumnName="id_renouvellement")}
     * )
     */
    private $renouvellements;

    /**
     * CompteTicket constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->renouvellements = new ArrayCollection();
    }

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

    /**
     * Add renouvellement
     *
     * @param Renouvellement $renouvellement
     *
     * @return CompteTicket
     */
    public function addRenouvellement(Renouvellement $renouvellement)
    {
        $this->renouvellements[] = $renouvellement;

        return $this;
    }

    /**
     * Remove renouvellement
     *
     * @param Renouvellement $renouvellement
     */
    public function removeRenouvellement(Renouvellement $renouvellement)
    {
        $this->renouvellements->removeElement($renouvellement);
    }

    /**
     * Get renouvellements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRenouvellements()
    {
        return $this->renouvellements;
    }
}
