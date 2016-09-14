<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * AbstractMasterController class file
 *
 * PHP Version 5.6
 *
 * @category Controller
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * AbstractMasterController class
 *
 * @category Controller
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
abstract class AbstractMasterController extends Controller
{
    /**
     * @param \Exception $exception
     * @return Response
     */
    protected function renderErrorPage(\Exception $exception)
    {
        return new Response('error');
    }
}
