<?php

namespace CoreBundle\Tests\Enum;

use CoreBundle\Enum\ModePaiementEnum;
use MasterBundle\Enum\ExceptionCodeEnum;
use MasterBundle\Exception\EmagException;

/**
 * ModePaiementEnumTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * ModePaiementEnumTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class ModePaiementEnumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @uses vérifie que la méthode renvoyant un objet d'opération lève bien une exception
     *               dans le cas ou le type de mode de paiement n'est pas valide
     * @covers ModePaiementEnum::createNewOperation
     */
    public function testFailCreateNewOperation()
    {
        $this->expectException(EmagException::class);
        $this->expectExceptionCode(ExceptionCodeEnum::PAS_VALEUR_ATTENDUE);

        // utilisation de la méthode qui doit retrouner un objet de type AbstractOperation
        ModePaiementEnum::createNewOperation(-1);
    }
}