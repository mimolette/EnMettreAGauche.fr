<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PorteMonnaie class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * PorteMonnaie class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_porte_monnaie")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\PorteMonnaieRepository")
 */
class PorteMonnaie extends CompteSolde
{


    /**
     * Add ajustement
     *
     * @param \CoreBundle\Entity\AjustementSolde $ajustement
     *
     * @return PorteMonnaie
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
