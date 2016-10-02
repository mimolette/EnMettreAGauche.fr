<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VirementExterne class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * VirementExterne class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_virement_externe")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\VirementExterneRepository")
 */
class VirementExterne extends Operation
{
    

    /**
     * Set modePaiement
     *
     * @param \CoreBundle\Entity\ModePaiement $modePaiement
     *
     * @return VirementExterne
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
