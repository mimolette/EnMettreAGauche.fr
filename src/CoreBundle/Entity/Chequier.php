<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Chequier class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * Chequier class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_chequier")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\ChequierRepository")
 */
class Chequier
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_chequier", type="integer")
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
     * @var int
     *
     * @ORM\Column(name="nb_cheques", type="integer", nullable=true)
     */
    private $nbCheques;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = true;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CoreBundle\Entity\OperationCheque", mappedBy="chequier")
     */
    private $operations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->operations = new ArrayCollection();
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
     * @return Chequier
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
     * @return Chequier
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
     * Set nbCheques
     *
     * @param integer $nbCheques
     *
     * @return Chequier
     */
    public function setNbCheques($nbCheques)
    {
        $this->nbCheques = $nbCheques;

        return $this;
    }

    /**
     * Get nbCheques
     *
     * @return int
     */
    public function getNbCheques()
    {
        return $this->nbCheques;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Chequier
     */
    public function setActive($active)
    {
        $this->active = $active;

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
     * @param OperationCheque $operation
     *
     * @return Chequier
     */
    public function addOperation(OperationCheque $operation)
    {
        $this->operations[] = $operation;

        return $this;
    }

    /**
     * Remove operation
     *
     * @param OperationCheque $operation
     */
    public function removeOperation(OperationCheque $operation)
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
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
}
