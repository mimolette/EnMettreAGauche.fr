<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AjustementSolde class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * AjustementSolde class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_ajustement_solde")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\AjustementSoldeRepository")
 */
class AjustementSolde
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_ajustement_solde", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="solde_avant", type="float", nullable=true)
     */
    private $soldeAvant;

    /**
     * @var float
     *
     * @ORM\Column(name="solde_apres", type="float")
     */
    private $soldeApres;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ajustement_solde", type="date")
     */
    private $date;

    /**
     * AjustementSolde constructor.
     */
    public function __construct()
    {
        // utilisation de la date du jour par defaut
        $date = new \DateTime();
        $this->setDate($date);
    }

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
     * Set soldeAvant
     *
     * @param float $soldeAvant
     *
     * @return AjustementSolde
     */
    public function setSoldeAvant($soldeAvant)
    {
        $this->soldeAvant = $soldeAvant;

        return $this;
    }

    /**
     * Get soldeAvant
     *
     * @return float
     */
    public function getSoldeAvant()
    {
        // renvoi 0.0 si le solde est null
        if (null === $this->soldeAvant) {
            return 0.0;
        } else {
            return $this->soldeAvant;
        }
    }

    /**
     * Set soldeApres
     *
     * @param float $soldeApres
     *
     * @return AjustementSolde
     */
    public function setSoldeApres($soldeApres)
    {
        $this->soldeApres = $soldeApres;

        return $this;
    }

    /**
     * Get soldeApres
     *
     * @return float
     */
    public function getSoldeApres()
    {
        return $this->soldeApres;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return AjustementSolde
     */
    public function setDate(\DateTime $date)
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
