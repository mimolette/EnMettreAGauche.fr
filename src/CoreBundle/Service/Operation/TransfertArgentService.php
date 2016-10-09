<?php

namespace CoreBundle\Service\Operation;

use CoreBundle\Entity\TransfertArgent;
use MasterBundle\Exception\EmagException;

/**
 * TransfertArgentService class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * TransfertArgentService class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class TransfertArgentService
{
    public function updateComptesSoldes(TransfertArgent $transfert)
    {
        // TODO : ici poursuivre traitement
    }

    /**
     * @param TransfertArgent $transfert
     * @throws EmagException
     */
    private function verificationsTransfert(TransfertArgent $transfert)
    {

    }
}