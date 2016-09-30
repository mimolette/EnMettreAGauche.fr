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

    /**
     * Add paiement
     *
     * @param \CoreBundle\Entity\PaiementOperation $paiement
     *
     * @return VirementInterne
     */
    public function addPaiement(\CoreBundle\Entity\PaiementOperation $paiement)
    {
        $this->paiements[] = $paiement;

        return $this;
    }

    /**
     * Remove paiement
     *
     * @param \CoreBundle\Entity\PaiementOperation $paiement
     */
    public function removePaiement(\CoreBundle\Entity\PaiementOperation $paiement)
    {
        $this->paiements->removeElement($paiement);
    }

    /**
     * Get paiements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPaiements()
    {
        return $this->paiements;
    }
}
