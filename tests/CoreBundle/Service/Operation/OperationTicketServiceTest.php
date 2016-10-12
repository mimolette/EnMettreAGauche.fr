<?php

namespace CoreBundle\Tests\Service\Operation;

use MasterBundle\Test\AbstractMasterService;

/**
 * OperationTicketServiceTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * OperationTicketServiceTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class OperationTicketServiceTest extends AbstractMasterService
{
    /**
     * @return OperationTicketServiceTest
     */
    public function testVideService()
    {
        $this->setUp();

        return $this->get('emag.core.operation.ticket');
    }
}
