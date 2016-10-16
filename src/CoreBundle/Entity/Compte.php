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
 *          "compte" = "CoreBundle\Entity\Compte",
 *          "ticket" = "CoreBundle\Entity\CompteTicket"
 *      }
 * )
 */
class Compte
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_compte", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=255, nullable=true)
     */
    protected $numero;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active = true;

    /**
     * @var int
     *
     * @ORM\Column(name="solde", type="float")
     */
    protected $solde;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_mise_jour", type="date")
     */
    protected $lastMiseJour;

    /**
     * @var TypeCompte
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\TypeCompte")
     * @ORM\JoinColumn(name="type_compte_id", referencedColumnName="id_type_compte")
     */
    protected $type;

    /**
     * @var Couleur
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Couleur")
     * @ORM\JoinColumn(name="couleur_id", referencedColumnName="id_couleur")
     */
    protected $couleur;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CoreBundle\Entity\AbstractOperation", mappedBy="compte")
     */
    protected $operations;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CoreBundle\Entity\TransfertArgent", mappedBy="compteCrediteur")
     */
    protected $virementCrediteurs;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="CoreBundle\Entity\AjustementSolde",
     *     mappedBy="compte",
     *     cascade={"persist"}
     * )
     */
    protected $ajustements;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CoreBundle\Entity\Chequier", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="emag_compte_chequier",
     *     joinColumns={@ORM\JoinColumn(name="compte_id", referencedColumnName="id_compte")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="chequier_id", referencedColumnName="id_chequier")}
     * )
     */
    protected $chequiers;

    /**
     * Compte constructor.
     */
    public function __construct()
    {
        $this->operations         = new ArrayCollection();
        $this->virementCrediteurs = new ArrayCollection();
        $this->ajustements        = new ArrayCollection();
        $this->chequiers          = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $compteNom = '';

        // renvoi du nom ou du numéro ou les deux
        if ($this->getNom()) {
            $compteNom .= $this->getNom();
            if ($this->getNumero()) {
                $compteNom .= ' ('.$this->getNumero().')';
            }
        } else {
            $compteNom .= $this->getNumero();
        }

        return $compteNom;
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
     * @param AbstractOperation $operation
     *
     * @return Compte
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
     * Add virementCrediteur
     *
     * @param TransfertArgent $virementCrediteur
     *
     * @return Compte
     */
    public function addVirementCrediteur(TransfertArgent $virementCrediteur)
    {
        $this->virementCrediteurs[] = $virementCrediteur;

        return $this;
    }

    /**
     * Remove virementCrediteur
     *
     * @param TransfertArgent $virementCrediteur
     */
    public function removeVirementCrediteur(TransfertArgent $virementCrediteur)
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
     * Set solde
     *
     * @param float $solde
     *
     * @return Compte
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
        // renvoi 0.0 si le solde est null
        if (null === $this->solde) {
            return 0.0;
        } else {
            return $this->solde;
        }
    }

    /**
     * Add ajustement
     *
     * @param AjustementSolde $ajustement
     *
     * @return Compte
     */
    public function addAjustement(AjustementSolde $ajustement)
    {
        $this->ajustements[] = $ajustement;

        return $this;
    }

    /**
     * Remove ajustement
     *
     * @param AjustementSolde $ajustement
     */
    public function removeAjustement(AjustementSolde $ajustement)
    {
        $this->ajustements->removeElement($ajustement);
    }

    /**
     * Get ajustements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAjustements()
    {
        return $this->ajustements;
    }

    /**
     * Add chequier
     *
     * @param Chequier $chequier
     *
     * @return Compte
     */
    public function addChequier(Chequier $chequier)
    {
        $this->chequiers[] = $chequier;

        return $this;
    }

    /**
     * Remove chequier
     *
     * @param Chequier $chequier
     */
    public function removeChequier(Chequier $chequier)
    {
        $this->chequiers->removeElement($chequier);
    }

    /**
     * Get chequiers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChequiers()
    {
        return $this->chequiers;
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

    /**
     * Set lastMiseJour
     *
     * @param \DateTime $lastMiseJour
     *
     * @return Compte
     */
    public function setLastMiseJour($lastMiseJour)
    {
        $this->lastMiseJour = $lastMiseJour;

        return $this;
    }

    /**
     * Get lastMiseJour
     *
     * @return \DateTime
     */
    public function getLastMiseJour()
    {
        return $this->lastMiseJour;
    }
}
