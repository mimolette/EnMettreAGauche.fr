<?php

namespace CoreBundle\Tests\Enum;

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
}