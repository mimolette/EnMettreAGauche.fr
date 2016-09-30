<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaiementOperation class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * PaiementOperation class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_paiement_operation")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\PaiementOperationRepository")
 */
class PaiementOperation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_paiement_operation", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_tickets", type="integer", nullable=true)
     */
    private $nbTickets;

    /**
     * @var Operation
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Operation", inversedBy="paiements")
     * @ORM\JoinColumn(name="operation_id", referencedColumnName="id_operation")
     */
    private $operation;

    /**
     * @var ModePaiement
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\ModePaiement")
     * @ORM\JoinColumn(name="mode_paiement_id", referencedColumnName="id_mode_paiement")
     */
    private $modePaiement;

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
     * @return PaiementOperation
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
     * Set operation
     *
     * @param Operation $operation
     *
     * @return PaiementOperation
     */
    public function setOperation(Operation $operation = null)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation
     *
     * @return Operation
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Set modePaiement
     *
     * @param ModePaiement $modePaiement
     *
     * @return PaiementOperation
     */
    public function setModePaiement(ModePaiement $modePaiement = null)
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    /**
     * Get modePaiement
     *
     * @return ModePaiement
     */
    public function getModePaiement()
    {
        return $this->modePaiement;
    }
}
