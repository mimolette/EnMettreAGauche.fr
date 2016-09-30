<?php

namespace CoreBundle\Entity;

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
        return $this->solde;
    }
}
