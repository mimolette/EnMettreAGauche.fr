<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * DefaultController class file
 *
 * PHP Version 5.6
 *
 * @category Controller
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * DefaultController class
 *
 * @category Controller
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class DefaultController extends AbstractMasterController
{
    /**
     * @Route("/", name="main_route")
     */
    public function indexAction()
    {
        try {
            return $this->render('CoreBundle:Default:index.html.twig');
        } catch (\Exception $exception) {
            return $this->renderErrorPage($exception);
        }
    }
}
