<?php
// src/OC/CoreBundler/Controller/CoreController.php

namespace OC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;



class CoreController extends Controller
{
    // Accueil
    public function indexAction()
    {       
        return $this->render('OCCoreBundle:Core:index.html.twig' );
    }

    // Contact
    public function contactAction(Request $request)
    {
        $session = $request->getSession();
        $session->getFlashBag()->add('info', 'La page de contact n\'est pas encore disponible. Merci de revenir plus tard.');
        return $this->redirectToRoute('oc_core_home');

        return $this->render('OCCoreBundle:Core:contact.html.twig' );
    }


}//endC