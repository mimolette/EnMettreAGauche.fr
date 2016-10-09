<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OperationTicket class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * OperationTicket class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_operation_ticket")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\OperationTicketRepository")
 */
class OperationTicket extends AbstractOperation
{
    /**
     * @var int
     *
     * @ORM\Column(name="nbTicket", type="integer")
     */
    private $nbTicket;

    /**
     * Set nbTicket
     *
     * @param integer $nbTicket
     *
     * @return OperationTicket
     */
    public function setNbTicket($nbTicket)
    {
        $this->nbTicket = $nbTicket;

        return $this;
    }

    /**
     * Get nbTicket
     *
     * @return int
     */
    public function getNbTicket()
    {
        return $this->nbTicket;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return OperationTicket
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param float $montantTicket
     */
    public function calculMontant($montantTicket)
    {
        // convertion du montant du ticket
        $montantTicket = floatval($montantTicket);

        $this->montant = $montantTicket * $this->getNbTicket();
    }
}
