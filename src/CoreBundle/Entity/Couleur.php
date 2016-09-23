<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Couleur class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * Couleur class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_couleur")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\CouleurRepository")
 */
class Couleur
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_couleur", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code_hexa", type="string", length=10, unique=true)
     */
    private $codeHexa;


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
     * Set codeHexa
     *
     * @param string $codeHexa
     *
     * @return Couleur
     */
    public function setCodeHexa($codeHexa)
    {
        $this->codeHexa = $codeHexa;

        return $this;
    }

    /**
     * Get codeHexa
     *
     * @return string
     */
    public function getCodeHexa()
    {
        return $this->codeHexa;
    }
}
