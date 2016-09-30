<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CompteCheque class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * CompteCheque class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_compte_cheque")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\CompteChequeRepository")
 */
class CompteCheque extends CompteSolde
{
    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CoreBundle\Entity\Chequier")
     * @ORM\JoinTable(
     *     name="emag_compte_chequier",
     *     joinColumns={@ORM\JoinColumn(name="compte_id", referencedColumnName="id_compte")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="chequier_id", referencedColumnName="id_chequier")}
     * )
     */
    private $chequiers;

    /**
     * Add chequier
     *
     * @param Chequier $chequier
     *
     * @return CompteCheque
     */
    public function addChequier(Chequier $chequier)
    {
        $this->chequiers[] = $chequier;

        return $this;
    }

    /**
     * Remove chequier
     *
     * @param Chequier $chequier
     */
    public function removeChequier(Chequier $chequier)
    {
        $this->chequiers->removeElement($chequier);
    }

    /**
     * Get chequiers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChequiers()
    {
        return $this->chequiers;
    }
}
