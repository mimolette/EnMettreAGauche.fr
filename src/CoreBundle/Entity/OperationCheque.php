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
class OperationCheque extends Operation
{
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
     * Set modePaiement
     *
     * @param \CoreBundle\Entity\ModePaiement $modePaiement
     *
     * @return OperationCheque
     */
    public function setModePaiement(\CoreBundle\Entity\ModePaiement $modePaiement = null)
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    /**
     * Get modePaiement
     *
     * @return \CoreBundle\Entity\ModePaiement
     */
    public function getModePaiement()
    {
        return $this->modePaiement;
    }
}
