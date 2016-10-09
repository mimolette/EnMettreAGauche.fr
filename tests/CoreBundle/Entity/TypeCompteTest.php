<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\TypeCompte;

/**
 * TypeCompteTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * TypeCompteTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class TypeCompteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return TypeCompte
     * @covers TypeCompte::getId
     */
    public function testVideTypeCompte()
    {
        // création d'un nouveau type de compte
        $type = new TypeCompte;
        $this->assertNull($type->getId());

        return $type;
    }
}