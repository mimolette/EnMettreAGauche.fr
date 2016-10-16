<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prelevement class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * Prelevement class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_prelevement")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\PrelevementRepository")
 */
class Prelevement extends AbstractOperation
{


    /**
     * Get comptabilise
     *
     * @return boolean
     */
    public function getComptabilise()
    {
        return $this->comptabilise;
    }
}
