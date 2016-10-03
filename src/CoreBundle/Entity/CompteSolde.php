<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CompteSolde class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * CompteSolde class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_compte_solde")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\CompteSoldeRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type_compte_solde", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *          "autre" = "CoreBundle\Entity\AutreCompte",
 *          "monnaie" = "CoreBundle\Entity\PorteMonnaie",
 *          "cheque" = "CoreBundle\Entity\CompteCheque"
 *      }
 * )
 */
abstract class CompteSolde extends Compte
{
    /**
     * @var float
     *
     * @ORM\Column(name="solde", type="float")
     */
    protected $solde;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CoreBundle\Entity\AjustementSolde", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="emag_compte_ajustements",
     *     joinColumns={@ORM\JoinColumn(name="compte_id", referencedColumnName="id_compte")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="ajustement_solde_id", referencedColumnName="id_ajustement_solde")}
     * )
     */
    protected $ajustements;

    /**
     * Set solde
     *
     * @param float $solde
     *
     * @return CompteSolde
     */
    public function setSolde($solde)
    {
        $this->solde = $solde;

        return $this;
    }

    /**
     * Get solde
     *
     * @return float
     */
    public function getSolde()
    {
        // renvoi 0.0 si le solde est null
        if (null === $this->solde) {
            return 0.0;
        } else {
            return $this->solde;
        }
    }

    /**
     * Add ajustement
     *
     * @param AjustementSolde $ajustement
     *
     * @return CompteSolde
     */
    public function addAjustement(AjustementSolde $ajustement)
    {
        $this->ajustements[] = $ajustement;

        return $this;
    }

    /**
     * Remove ajustement
     *
     * @param AjustementSolde $ajustement
     */
    public function removeAjustement(AjustementSolde $ajustement)
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
