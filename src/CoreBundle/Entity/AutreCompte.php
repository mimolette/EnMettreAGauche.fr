<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AutreCompte class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * AutreCompte class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_autre_compte")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\AutreCompteRepository")
 */
class AutreCompte extends CompteSolde
{


    /**
     * Add ajustement
     *
     * @param \CoreBundle\Entity\AjustementSolde $ajustement
     *
     * @return AutreCompte
     */
    public function addAjustement(\CoreBundle\Entity\AjustementSolde $ajustement)
    {
        $this->ajustements[] = $ajustement;

        return $this;
    }

    /**
     * Remove ajustement
     *
     * @param \CoreBundle\Entity\AjustementSolde $ajustement
     */
    public function removeAjustement(\CoreBundle\Entity\AjustementSolde $ajustement)
    {
        $this->ajustements->removeElement($ajustement);
    }

    /**
     * Get ajustements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAjustements()
    {
        return $this->ajustements;
    }
}
