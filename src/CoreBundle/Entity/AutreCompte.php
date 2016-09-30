<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AutreCompte class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * AutreCompte class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_autre_compte")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\AutreCompteRepository")
 */
class AutreCompte extends CompteSolde
{

}
