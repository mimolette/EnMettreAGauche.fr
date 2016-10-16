<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OperationEspeces class file
 *
 * PHP Version 5.6
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * OperationEspeces class
 *
 * @category Entity
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 *
 * @ORM\Table(name="emag_operation_especes")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\OperationEspecesRepository")
 */
class OperationEspeces extends AbstractOperation
{
    

    /**
     * Get comptabilise
     *
     * @return boolean
     */
    public function getComptabilise()
    {
        return $this->comptabilise;
    }
}
