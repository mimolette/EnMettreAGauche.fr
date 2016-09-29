<?php

namespace CoreBundle\Entity;

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
    private $active;


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
    public function getActive()
    {
        return $this->active;
    }
}

