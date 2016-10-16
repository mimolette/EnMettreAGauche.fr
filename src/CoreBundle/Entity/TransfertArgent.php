<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TransfertArgent class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * TransfertArgent class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_virement_interne")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\TransfertArgentRepository")
 */
class TransfertArgent extends AbstractOperation
{
    /**
     * @var Compte
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Compte", inversedBy="virementCrediteurs")
     * @ORM\JoinColumn(name="compte_crediteur_id", referencedColumnName="id_compte")
     */
    private $compteCrediteur;

    /**
     * Set compteCrediteur
     *
     * @param Compte $compteCrediteur
     *
     * @return TransfertArgent
     */
    public function setCompteCrediteur(Compte $compteCrediteur = null)
    {
        $this->compteCrediteur = $compteCrediteur;

        return $this;
    }

    /**
     * Get compteCrediteur
     *
     * @return Compte
     */
    public function getCompteCrediteur()
    {
        return $this->compteCrediteur;
    }

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
