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
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type_opration", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *          "virement_interne" = "CoreBundle\Entity\VirementInterne",
 *          "courante" = "CoreBundle\Entity\OperationCourante",
 *          "virement" = "CoreBundle\Entity\Virement",
 *          "cheque" = "CoreBundle\Entity\OperationCheque",
 *          "especes" = "CoreBundle\Entity\OperationEspeces",
 *          "prelevement" = "CoreBundle\Entity\Prelevement",
 *          "ticket" = "CoreBundle\Entity\OperationTicket"
 *      }
 * )
 */
abstract class Operation
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_operation", type="date")
     */
    protected $date;

    /**
     * @var Compte
     * 
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Compte", inversedBy="operations")
     * @ORM\JoinColumn(name="compte_id", referencedColumnName="id_compte")
     */
    protected $compte;

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
     * @var ModePaiement
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\ModePaiement")
     * @ORM\JoinColumn(name="mode_paiement_id", referencedColumnName="id_mode_paiement")
     */
    protected $modePaiement;

    /**
     * Operation constructor.
     */
    public function __construct()
    {
        $this->catogories = new ArrayCollection();
        $this->paiements  = new ArrayCollection();
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
        // réciprocité
        $compte->addOperation($this);

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

    /**
     * Get paiements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPaiements()
    {
        return $this->paiements;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Operation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
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
}
