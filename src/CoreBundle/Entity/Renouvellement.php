<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Renouvellement class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * Renouvellement class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_renouvellement")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\RenouvellementRepository")
 */
class Renouvellement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_renouvellement", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_tickets", type="integer")
     */
    private $nbTickets;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_renouvellement", type="date")
     */
    private $date;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nbTickets
     *
     * @param integer $nbTickets
     *
     * @return Renouvellement
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Renouvellement
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
}
