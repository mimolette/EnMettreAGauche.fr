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
}
