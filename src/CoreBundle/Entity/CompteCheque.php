<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompteCheque class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * CompteCheque class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_compte_cheque")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\CompteChequeRepository")
 */
class CompteCheque extends CompteSolde
{

}

