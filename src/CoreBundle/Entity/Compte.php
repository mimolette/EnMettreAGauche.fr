<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Compte class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * Compte class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_compte")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\CompteRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type_compte", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *          "ticket" = "CoreBundle\Entity\CompteTicket"
 *      }
 * )
 */
abstract class Compte
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_compte", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=255, nullable=true)
     */
    private $numero;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var TypeCompte
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\TypeCompte")
     * @ORM\JoinColumn(name="type_compte_id", referencedColumnName="id_type_compte")
     */
    private $type;

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
     * @ORM\OneToMany(targetEntity="CoreBundle\Entity\Operation", mappedBy="compte")
     */
    private $operations;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CoreBundle\Entity\VirementInterne", mappedBy="compteCrediteur")
     */
    private $virementCrediteurs;

    /**
     * Compte constructor.
     */
    public function __construct()
    {
        $this->operations         = new ArrayCollection();
        $this->virementCrediteurs = new ArrayCollection();
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
     * @return Compte
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
     * Set numero
     *
     * @param string $numero
     *
     * @return Compte
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set type
     *
     * @param TypeCompte $type
     *
     * @return Compte
     */
    public function setType(TypeCompte $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return TypeCompte
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set couleur
     *
     * @param Couleur $couleur
     *
     * @return Compte
     */
    public function setCouleur(Couleur $couleur = null)
    {
        $this->couleur = $couleur;

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
     * Add operation
     *
     * @param Operation $operation
     *
     * @return Compte
     */
    public function addOperation(Operation $operation)
    {
        $this->operations[] = $operation;

        return $this;
    }

    /**
     * Remove operation
     *
     * @param Operation $operation
     */
    public function removeOperation(Operation $operation)
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
     * Add virementCrediteur
     *
     * @param VirementInterne $virementCrediteur
     *
     * @return Compte
     */
    public function addVirementCrediteur(VirementInterne $virementCrediteur)
    {
        $this->virementCrediteurs[] = $virementCrediteur;

        return $this;
    }

    /**
     * Remove virementCrediteur
     *
     * @param VirementInterne $virementCrediteur
     */
    public function removeVirementCrediteur(VirementInterne $virementCrediteur)
    {
        $this->virementCrediteurs->removeElement($virementCrediteur);
    }

    /**
     * Get virementCrediteurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVirementCrediteurs()
    {
        return $this->virementCrediteurs;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
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
