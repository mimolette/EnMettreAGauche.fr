<?php

namespace EmagUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * LoginController class file
 *
 * PHP Version 5.6
 *
 * @category Controller
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * LoginController class
 *
 * @category Controller
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class LoginController extends Controller
{
    /**
     * @Route("/login", name="login_perso")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
        return $this->render('EmagUserBundle::login.html.twig');
    }
}
