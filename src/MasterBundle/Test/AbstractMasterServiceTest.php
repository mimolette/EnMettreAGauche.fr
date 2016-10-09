<?php

namespace MasterBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AbstractMasterService extends KernelTestCase
{
    /** @var ContainerInterface */
    private $container;

    public function setUp()
    {
        self::bootKernel();

        $this->container = self::$kernel->getContainer();
    }

    /**
     * @param $serviceName
     * @return object
     */
    public function get($serviceName)
    {
        return $this->container->get($serviceName);
    }
}
