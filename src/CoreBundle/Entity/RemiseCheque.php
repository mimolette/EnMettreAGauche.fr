<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RemiseCheque class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * RemiseCheque class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_remise_cheque")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\RemiseChequeRepository")
 */
class RemiseCheque extends AbstractOperation
{

}

