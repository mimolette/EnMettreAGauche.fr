<?php

namespace CoreBundle\Service\Operation;

use CoreBundle\Entity\AbstractOperation;
use CoreBundle\Entity\OperationTicket;
use MasterBundle\Exception\EmagException;

/**
 * OperationTicketService class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
/**
 * OperationTicketService class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class OperationTicketService extends AbstractOperationService
{
    /**
     * @uses cette fonction regroupe toutes les vérifications à effectués sur les
     * opérations de type ticket
     * @param OperationTicket $operation
     * @param bool            $throwException
     * @return bool
     * @throws EmagException
     */
    public function isTicketOperationValide(OperationTicket $operation, $throwException = true)
    {
        // appel aux services de compte, de type de compte et de mode de paiement
        $cService = $this->compteService;
        $tService = $this->typeCompteService;

        // récupération du compte, type de compte et du mode de paiement de l'opération
        $compte = $this->getCompte($operation);
        $typeCompte = $cService->getTypeCompte($compte);
        $modePaiementOpe = $this->getModePaiement($operation);

        // vérifie si l'opération possèdent bien un nombre de ticket valide
        $montantOpe = $operation->getMontant();
        $mService->isMontantOperationValide($montantOpe, $modePaiementOpe);

        // vérifie si le compte est actif
        $cService->isCompteActif($compte);

        // vérifie si le compte autorise ce genre de mode de paiement
        $tService->isModePaiementAutorise($modePaiementOpe, $typeCompte);

        // aucune vérification n'as levée d'exception, l'opération est valide
        // mise a jour du montant de l'opération

        return true;
    }

    /**
     * @uses cette fonction met à jour le montant de l'opération de ticket en fonction
     * du nombre de ticket et d'un montant des ticket du compte
     * @param int  $nbTicket
     * @return bool
     * @throws EmagException
     */
    public function guessMontant(OperationTicket $operation)
    {
        // TODO : implémenter cette méthode
        return true;
    }

    /**
     * @uses cette fonction vérifie que le nombre de ticket est valide, a savoir un entier
     * positif
     * @param int  $nbTicket
     * @param bool $throwException
     * @return bool
     * @throws EmagException
     */
    public function isNbTicketValide($nbTicket, $throwException = true)
    {
        // TODO : implémenter cette méthode
        return true;
    }
}