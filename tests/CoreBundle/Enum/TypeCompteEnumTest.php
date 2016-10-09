<?php

namespace CoreBundle\Tests\Enum;

use CoreBundle\Enum\ModePaiementEnum;
use CoreBundle\Enum\TypeCompteEnum;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * TypeCompteEnumTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * TypeCompteEnumTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class TypeCompteEnumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @uses vérifie que la méthode renvoyant un objet de compte lève bien une exception
     *               dans le cas ou le type de compte n'est pas valide
     * @covers ModePaiementEnum::createNewOperation
     */
    public function testFailCreateNewOperation()
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::PAS_VALEUR_ATTENDUE);

        // utilisation de la méthode qui doit retrouner un objet de type Compte
        TypeCompteEnum::createNewCompte(-1);
    }

    /**
     * @uses vérifie que la méthode indiquant si un type de compte donnée autorise ou non
     *               les ajustement lève un excpetion dans le cas ou un type de compte
     *               invalide est donné.
     * @covers ModePaiementEnum::autoriseAuxAjustements
     */
    public function testFailAutoriseAuxAjustements()
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::PAS_VALEUR_ATTENDUE);

        // utilistation de la méthode retournant si le type de compte autorise les ajustements
        TypeCompteEnum::autoriseAuxAjustements(-1);
    }

    /**
     * @uses verifie que la méthode retourne bien la valeur attendu, soit un boolean
     * @covers TypeCompteEnum::autoriseAuxAjustements
     */
    public function testAutoriseAuxAjustements()
    {
        $this->assertTrue(TypeCompteEnum::autoriseAuxAjustements(TypeCompteEnum::COMPTE_CHEQUE));
    }
}