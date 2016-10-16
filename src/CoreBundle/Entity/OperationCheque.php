<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OperationCheque class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * OperationCheque class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_operation_cheque")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\OperationChequeRepository")
 */
class OperationCheque extends AbstractOperation
{
    /**
     * @var bool
     *
     * @ORM\Column(name="encaisse", type="boolean")
     */
    private $encaisse = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="annule", type="boolean")
     */
    private $annule = false;
    
    /**
     * @var Chequier
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Chequier", inversedBy="operations")
     * @ORM\JoinColumn(name="chequier_id", referencedColumnName="id_chequier")
     */
    private $chequier;

    /**
     * Set chequier
     *
     * @param Chequier $chequier
     *
     * @return OperationCheque
     */
    public function setChequier(Chequier $chequier = null)
    {
        $this->chequier = $chequier;

        return $this;
    }

    /**
     * Get chequier
     *
     * @return Chequier
     */
    public function getChequier()
    {
        return $this->chequier;
    }

    /**
     * Get comptabilise
     *
     * @return boolean
     */
    public function getComptabilise()
    {
        return $this->comptabilise;
    }

    /**
     * Set encaisse
     *
     * @param boolean $encaisse
     *
     * @return OperationCheque
     */
    public function setEncaisse($encaisse)
    {
        $this->encaisse = $encaisse;

        return $this;
    }

    /**
     * Is encaisse
     *
     * @return boolean
     */
    public function isEncaisse()
    {
        return $this->encaisse;
    }

    /**
     * Get encaisse
     *
     * @return boolean
     */
    public function getEncaisse()
    {
        return $this->encaisse;
    }

    /**
     * Set annule
     *
     * @param boolean $annule
     *
     * @return OperationCheque
     */
    public function setAnnule($annule)
    {
        $this->annule = $annule;

        return $this;
    }

    /**
     * Is annule
     *
     * @return boolean
     */
    public function isAnnule()
    {
        return $this->annule;
    }
}
