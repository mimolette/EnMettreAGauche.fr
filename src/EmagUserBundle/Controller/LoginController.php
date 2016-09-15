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
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        // récupère les services d'authentifications
        $authService = $this->get('security.authorization_checker');
        $authenticationUtils = $this->get('security.authentication_utils');

        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($authService->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('main_route');
        }

        // renvoi la vue de formulaire de connexion
        return $this->render('EmagUserBundle:Default:login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }
}
