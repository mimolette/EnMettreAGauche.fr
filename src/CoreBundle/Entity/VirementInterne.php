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
class VirementInterne
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_virement_interne", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Compte", inversedBy="virementCrediteurs")
     * @ORM\JoinColumn(name="compte_id", referencedColumnName="id_compte")
     */
    private $compteCrediteur;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

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
