<?php

namespace CoreBundle\Service\Compte;

use CoreBundle\Entity\Chequier;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * ChequierService class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
/**
 * ChequierService class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class ChequierService
{
    /**
     * @param Chequier $chequier
     * @param bool     $throwException
     * @return bool
     * @throws EmagException
     */
    public function isChequierActif(Chequier $chequier, $throwException = true)
    {
        $actif = $chequier->isActive();
        if (!$actif && $throwException) {
            // lève une exception si le chequier n'est pas actif
            throw new EmagException(
                "Impossible d'effectuer l'opération sur le chequier ::$chequier car celui-ci est inactif.",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        return $actif;
    }

    /**
     * @uses fonction qui réduit le nombre de chequier disponible dans le chequier de 1
     * si le nombre de cheques disponible devvient égale à 0 alors le chequier doit devenir
     * inactif
     * @param Chequier $chequier
     * @param bool     $throwException
     */
    public function utiliseUnCheque(Chequier $chequier, $throwException = true)
    {
        // vérifie que le chequier possède bien au moins un chèque
        $this->isNbChequeValidePourOperation($chequier, $throwException);

        // réduction du nombre de chèque
        $nbCheque = $chequier->getNbCheques();
        $nouveauSoldeCheque = $nbCheque-1;

        // mise à jour d'un nombre de chèque du chequier
        $chequier->setNbCheques($nouveauSoldeCheque);

        // si le nouveau solde de chèque est égale à 0 alors le chequier doit devenir inactif
        if ($nouveauSoldeCheque === 0) {
            $chequier->setActive(false);
        }
    }

    /**
     * @uses vérifie si le nombre de chèque encore disponible dans le chequier est valide pour
     * envisager une opération. Il faut donc que le nombre de chèque soit supérieur ou égale à
     * 1 pour que la méthode retourne vrai, faux dans le cas contraire.
     * @param Chequier $chequier
     * @param bool     $throwException
     * @return bool
     * @throws EmagException
     */
    public function isNbChequeValidePourOperation(Chequier $chequier, $throwException = true)
    {
        // validité du nombre de chèque du chequier
        $valide = true;

        // si le nombre de chèque est supérieur ou égale à 1
        $valide = $chequier->getNbCheques() >= 1;
        // si la méthode doit levée une exception
        if (!$valide && $throwException) {
            // lève une exception si le chequier ne possède pas un nombre de chèques valide
            throw new EmagException(
                "Le chequier ::$chequier ne possède aucun chèque disponible pour une opération.",
                ExceptionCodeEnum::OPERATION_IMPOSSIBLE,
                __METHOD__
            );
        }

        return $valide;
    }
}