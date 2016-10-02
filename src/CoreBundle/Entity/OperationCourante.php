<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OperationCourante class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * OperationCourante class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_operation_courante")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\OperationCouranteRepository")
 */
class OperationCourante extends Operation
{


    /**
     * Set modePaiement
     *
     * @param \CoreBundle\Entity\ModePaiement $modePaiement
     *
     * @return OperationCourante
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
