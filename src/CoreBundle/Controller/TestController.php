<?php

namespace CoreBundle\Controller;

use CoreBundle\Entity\AjustementSolde;
use CoreBundle\Enum\ModePaiementEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * TestController class file
 *
 * PHP Version 5.6
 *
 * @category Controller
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * TestController class
 *
 * @category Controller
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class TestController extends AbstractMasterController
{
    /**
     * @Route("/test", name="test_route")
     */
    public function indexAction()
    {
        try {
            $service = $this->get('emag.core.ajustement');

            $ajustement = new AjustementSolde();

            $compte = $service->getCompte($ajustement);

            return new Response('test done');
        } catch (\Exception $exception) {
            dump($exception);die();
        }

    }
}
