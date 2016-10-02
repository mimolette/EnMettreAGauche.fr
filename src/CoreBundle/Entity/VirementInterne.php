<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VirementInterne class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * VirementInterne class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_virement_interne")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\VirementInterneRepository")
 */
class VirementInterne extends Operation
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
     * @return VirementInterne
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
}
