<?php

namespace EmagUserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * EmagUser class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * EmagUser class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_user")
 * @ORM\Entity(repositoryClass="EmagUserBundle\Repository\EmagUserRepository")
 */
class EmagUser extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_emag_user", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CoreBundle\Entity\Compte")
     * @ORM\JoinTable(
     *     name="emag_user_compte",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id_emag_user")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="compte_id", referencedColumnName="id_compte", unique=true)}
     * )
     */
    private $comptes;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CoreBundle\Entity\Categorie")
     * @ORM\JoinTable(
     *     name="emag_user_categorie",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id_emag_user")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="categorie_id", referencedColumnName="id_categorie", unique=true)}
     * )
     */
    private $categories;

    /**
     * EmagUser constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->comptes    = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    /**
     * Add compte
     *
     * @param \CoreBundle\Entity\Compte $compte
     *
     * @return EmagUser
     */
    public function addCompte(\CoreBundle\Entity\Compte $compte)
    {
        $this->comptes[] = $compte;

        return $this;
    }

    /**
     * Remove compte
     *
     * @param \CoreBundle\Entity\Compte $compte
     */
    public function removeCompte(\CoreBundle\Entity\Compte $compte)
    {
        $this->comptes->removeElement($compte);
    }

    /**
     * Get comptes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComptes()
    {
        return $this->comptes;
    }

    /**
     * Add category
     *
     * @param \CoreBundle\Entity\Categorie $category
     *
     * @return EmagUser
     */
    public function addCategory(\CoreBundle\Entity\Categorie $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \CoreBundle\Entity\Categorie $category
     */
    public function removeCategory(\CoreBundle\Entity\Categorie $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
