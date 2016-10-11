<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * Categorie class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_categorie")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\CategorieRepository")
 */
class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_categorie", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = true;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CoreBundle\Entity\AbstractOperation", mappedBy="categories")
     */
    private $operations;

    /**
     * @var Couleur
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Couleur")
     * @ORM\JoinColumn(name="couleur_id", referencedColumnName="id_couleur")
     */
    private $couleur;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CoreBundle\Entity\Categorie")
     * @ORM\JoinTable(
     *     name="emag_categorie_enfant",
     *     joinColumns={@ORM\JoinColumn(name="categorie_id", referencedColumnName="id_categorie")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="categorie_enfant_id", referencedColumnName="id_categorie")}
     * )
     */
    private $enfants;

    /**
     * Categorie constructor.
     */
    public function __construct()
    {
        $this->operations = new ArrayCollection();
        $this->enfants    = new ArrayCollection();
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
     * @return Categorie
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
     * Set active
     *
     * @param boolean $active
     *
     * @return Categorie
     */
    public function setActive($active)
    {
        $this->active = $active;
        // propafation aux enfants
        $this->propageActiveAuxEnfants();

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Add operation
     *
     * @param AbstractOperation $operation
     *
     * @return Categorie
     */
    public function addOperation(AbstractOperation $operation)
    {
        $this->operations[] = $operation;

        return $this;
    }

    /**
     * Remove operation
     *
     * @param AbstractOperation $operation
     */
    public function removeOperation(AbstractOperation $operation)
    {
        $this->operations->removeElement($operation);
    }

    /**
     * Get operations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOperations()
    {
        return $this->operations;
    }

    /**
     * Set couleur
     *
     * @param Couleur $couleur
     *
     * @return Categorie
     */
    public function setCouleur(Couleur $couleur = null)
    {
        $this->couleur = $couleur;
        // propagation aux enfants
        $this->propageCouleurAuxEnfants();

        return $this;
    }

    /**
     * Get couleur
     *
     * @return Couleur
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * Add enfant
     *
     * @param Categorie $enfant
     *
     * @return Categorie
     */
    public function addEnfant(Categorie $enfant)
    {
        // affectation de la couleur et état du parent
        $enfant->setCouleur($this->couleur);
        $enfant->setActive($this->active);
        $this->enfants[] = $enfant;

        return $this;
    }

    /**
     * Remove enfant
     *
     * @param Categorie $enfant
     */
    public function removeEnfant(Categorie $enfant)
    {
        $this->enfants->removeElement($enfant);
    }

    /**
     * Get enfants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEnfants()
    {
        return $this->enfants;
    }

    /**
     * propageCouleurAuxEnfants
     */
    private function propageCouleurAuxEnfants()
    {
        // parcourt des différents enfants pour leur affecté la couleur
        $enfants = $this->getEnfants();
        /** @var Categorie $categorie */
        foreach ($enfants as $categorie) {
            // affectation de la couleur
            $categorie->setCouleur($this->couleur);
        }
    }

    /**
     * propageActiveAuxEnfants
     */
    private function propageActiveAuxEnfants()
    {
        // parcourt des différents enfants pour leur affecté l'activité
        $enfants = $this->getEnfants();
        /** @var Categorie $categorie */
        foreach ($enfants as $categorie) {
            // affectation de la couleur
            $categorie->setActive($this->active);
        }
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
}
