<?php
// src/OC/Platform/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//
class AdvertController extends Controller{
    
    // getAll
    public function indexAction($page){
        //return new Response("Notre page Hello World ?!");
        // Notre liste d'annonce en dur
        $listAdverts = array(
            array(
              'title'   => 'Recherche développpeur Symfony',
              'id'      => 1,
              'author'  => 'Alexandre',
              'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
              'date'    => new \Datetime()),
            array(
              'title'   => 'Mission de webmaster',
              'id'      => 2,
              'author'  => 'Hugo',
              'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
              'date'    => new \Datetime()),
            array(
              'title'   => 'Offre de stage webdesigner',
              'id'      => 3,
              'author'  => 'Mathieu',
              'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
              'date'    => new \Datetime())
          );

        if($page < 1){
            throw new NotFoundHttpException('Page "'.$page.'" inexistante');
        }
        return $this->render('OCPlatformBundle:Advert:index.html.twig', array('listAdverts' => $listAdverts ));
    }

    
   //get ID -- GET
   public function viewAction($id){
       //
       $advert = array(
        'title'   => 'Recherche développpeur Symfony2',
        'id'      => $id,
        'author'  => 'Alexandre',
        'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
        'date'    => new \Datetime()
      );

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array('advert' => $advert)) ;  
    }
    

    // Post - ADD
    public function addAction(Request $request){

            if($request->isMethod('POST')){
            // On récupère le service
            $antispam = $this->container->get('oc_platform.antispam');
            $text = '...';

            if ($antispam->isSpam($text)) {
              throw new \Exception('Votre message a été détecté comme spam !');
            }
               
            
            }//endIf
     return $this->render('OCPlatformBundle:Advert:add.html.twig');
   }



    // Put -- UPDATE
   public function editAction($id, Request $request){
        //
        $advert = array(
          'title'   => 'Recherche développpeur Symfony',
          'id'      => $id,
          'author'  => 'Alexandre',
          'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
          'date'    => new \Datetime()
    );


    if($request->isMethod('POST')){
        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifié');
        return $this->redirectToRoute('oc_platform_view', array('id' => 5));
    }//endIf

    return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
      'advert' => $advert
    ));
   }

    // Delete -- DELETE
   public function deleteAction($id, Request $request){
    
    $session = $request->getSession();
    $session->getFlashBag()->add('notice', 'Supprimer cette annonce n\'est pas encore possible');
    return $this->redirectToRoute('oc_platform_view', array('id' => $id));

        //return $this->render('OCPlatformBundle:Advert:delete.html.twig');
   }



   // MENU des annonces
   public function menuAction($limit){
    // On fixe en dur une liste ici, bien entendu par la suite
    // on la récupérera depuis la BDD !
    $listAdverts = array(
      array('id' => 2, 'title' => 'Recherche développeur Symfony'),
      array('id' => 5, 'title' => 'Mission de webmaster'),
      array('id' => 9, 'title' => 'Offre de stage webdesigner')
    );

    return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
      // Tout l'intérêt est ici : le contrôleur passe
      // les variables nécessaires au template !
     'listAdverts' => $listAdverts,  'limit' => $limit, 
    ));
  }

}//endC