<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Operation class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * Operation class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_operation")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\OperationRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type_opration", type="string")
 * @ORM\DiscriminatorMap({"classique" = "CoreBundle\Entity\Operation", "virement" = "CoreBundle\Entity\VirementInterne"})
 */
class Operation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_operation", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="float")
     */
    protected $montant;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="text", nullable=true)
     */
    protected $libelle;

    /**
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Compte", inversedBy="operations")
     * @ORM\JoinColumn(name="compte_id", referencedColumnName="id_compte")
     */
    protected $compte;

    /**
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\ModePaiement")
     * @ORM\JoinColumn(name="mode_paiement_id", referencedColumnName="id_mode_paiement")
     */
    protected $modePaiement;

    /**
     * @var ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="CoreBundle\Entity\Categorie", inversedBy="operations")
     * @ORM\JoinTable(name="emag_operation_categorie",
     *     joinColumns={@ORM\JoinColumn(name="operation_id", referencedColumnName="id_operation")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="categorie_id", referencedColumnName="id_categorie", unique=true)}
     *  )
     */
    protected $catogories;

    /**
     * Operation constructor.
     */
    public function __construct()
    {
        $this->catogories = new ArrayCollection();
    }

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
     * Set montant
     *
     * @param float $montant
     *
     * @return Operation
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return float
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Operation
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set compte
     *
     * @param Compte $compte
     *
     * @return Operation
     */
    public function setCompte(Compte $compte = null)
    {
        $this->compte = $compte;

        return $this;
    }

    /**
     * Get compte
     *
     * @return Compte
     */
    public function getCompte()
    {
        return $this->compte;
    }

    /**
     * Set modePaiement
     *
     * @param ModePaiement $modePaiement
     *
     * @return Operation
     */
    public function setModePaiement(ModePaiement $modePaiement = null)
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    /**
     * Get modePaiement
     *
     * @return ModePaiement
     */
    public function getModePaiement()
    {
        return $this->modePaiement;
    }

    /**
     * Add catogory
     *
     * @param Categorie $catogory
     *
     * @return Operation
     */
    public function addCatogory(Categorie $catogory)
    {
        // mise a jour inverse de la categorie
        $catogory->addOperation($this);
        $this->catogories[] = $catogory;

        return $this;
    }

    /**
     * Remove catogory
     *
     * @param Categorie $catogory
     */
    public function removeCatogory(Categorie $catogory)
    {
        $this->catogories->removeElement($catogory);
    }

    /**
     * Get catogories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCatogories()
    {
        return $this->catogories;
    }
}
