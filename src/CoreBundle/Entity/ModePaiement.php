<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModePaiement class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * ModePaiement class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_mode_paiement")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\ModePaiementRepository")
 */
class ModePaiement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_mode_paiement", type="integer")
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
     * @var bool
     *
     * @ORM\Column(name="etre_negatif", type="boolean")
     */
    private $etreNegatif;

    /**
     * @var bool
     *
     * @ORM\Column(name="etre_positif", type="boolean")
     */
    private $etrePositif;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_unique", type="integer", unique=true)
     */
    private $numeroUnique;


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
     * @return ModePaiement
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
     * Set etreNegatif
     *
     * @param boolean $etreNegatif
     *
     * @return ModePaiement
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
     * Set numeroUnique
     *
     * @param integer $numeroUnique
     *
     * @return ModePaiement
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
     * @return boolean
     */
    public function isEtrePositif()
    {
        return $this->etrePositif;
    }

    /**
     * @param boolean $etrePositif
     */
    public function setEtrePositif($etrePositif)
    {
        $this->etrePositif = $etrePositif;
    }
}
