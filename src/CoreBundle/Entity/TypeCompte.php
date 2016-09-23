<?php

namespace CoreBundle\Entity;

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
}