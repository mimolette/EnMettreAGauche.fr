<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TypeCompte class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * TypeCompte class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_type_compte")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\TypeCompteRepository")
 */
class TypeCompte
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_type_compte", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_unique", type="integer", unique=true)
     */
    private $numeroUnique;

    /**
     * @var bool
     *
     * @ORM\Column(name="etre_negatif", type="boolean")
     */
    private $etreNegatif;

    /**
     * @var bool
     *
     * @ORM\Column(name="virementAutorise", type="boolean")
     */
    private $virementAutorise;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CoreBundle\Entity\ModePaiement")
     * @ORM\JoinTable(
     *     name="emag_type_compte_mode_paiement",
     *     joinColumns={@ORM\JoinColumn(name="type_compte_id", referencedColumnName="id_type_compte")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="mode_paiement_id", referencedColumnName="id_mode_paiement")}
     * )
     */
    private $modePaiements;

    /**
     * TypeCompte constructor.
     */
    public function __construct()
    {
        $this->modePaiements = new ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     *
     * @return TypeCompte
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set numeroUnique
     *
     * @param integer $numeroUnique
     *
     * @return TypeCompte
     */
    public function setNumeroUnique($numeroUnique)
    {
        $this->numeroUnique = $numeroUnique;

        return $this;
    }

    /**
     * Get numeroUnique
     *
     * @return int
     */
    public function getNumeroUnique()
    {
        return $this->numeroUnique;
    }

    /**
     * Set etreNegatif
     *
     * @param boolean $etreNegatif
     *
     * @return TypeCompte
     */
    public function setEtreNegatif($etreNegatif)
    {
        $this->etreNegatif = $etreNegatif;

        return $this;
    }

    /**
     * Get etreNegatif
     *
     * @return bool
     */
    public function getEtreNegatif()
    {
        return $this->etreNegatif;
    }

    /**
     * Set virementAutorise
     *
     * @param boolean $virementAutorise
     *
     * @return TypeCompte
     */
    public function setVirementAutorise($virementAutorise)
    {
        $this->virementAutorise = $virementAutorise;

        return $this;
    }

    /**
     * Get virementAutorise
     *
     * @return bool
     */
    public function getVirementAutorise()
    {
        return $this->virementAutorise;
    }

    /**
     * Add modePaiement
     *
     * @param ModePaiement $modePaiement
     *
     * @return TypeCompte
     */
    public function addModePaiement(ModePaiement $modePaiement)
    {
        $this->modePaiements[] = $modePaiement;

        return $this;
    }

    /**
     * Remove modePaiement
     *
     * @param ModePaiement $modePaiement
     */
    public function removeModePaiement(ModePaiement $modePaiement)
    {
        $this->modePaiements->removeElement($modePaiement);
    }

    /**
     * Get modePaiements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getModePaiements()
    {
        return $this->modePaiements;
    }
}
