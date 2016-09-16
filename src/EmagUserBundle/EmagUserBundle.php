<?php

namespace EmagUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EmagUserBundle extends Bundle
{
    /**
     * @return string
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
