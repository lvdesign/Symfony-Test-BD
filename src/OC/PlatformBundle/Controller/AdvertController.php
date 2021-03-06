<?php
// src/OC/Platform/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//
class AdvertController extends Controller {
    
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
   public function viewAction($id)
  {
    // On récupère le repository
    $repository = $this->getDoctrine()
      ->getManager()
      ->getRepository('OCPlatformBundle:Advert')
    ;

    // On récupère l'entité correspondante à l'id $id
    $advert = $repository->find($id);

    // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
    // ou null si l'id $id  n'existe pas, d'où ce if :
    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // Le render ne change pas, on passait avant un tableau, maintenant un objet
    return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
      'advert' => $advert
    ));
  }
    

    // Post - ADD
    public function addAction(Request $request){
    // Création de l'entité
    $advert = new Advert();
    $advert->setTitle('Recherche développeur Symfony.');
    $advert->setAuthor('Alexandre');
    $advert->setContent("Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…");
    // On peut ne pas définir ni la date ni la publication,
    // car ces attributs sont définis automatiquement dans le constructeur

    //Image
    $image = new Image();
    $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
    $image->setAlt('Job de rêve');

    //liaison image a advert
    $advert->setImage($image);


    // On récupère l'EntityManager
    $em = $this->getDoctrine()->getManager();

    // Étape 1 : On « persiste » l'entité
    $em->persist($advert);

    // Étape 2 : On « flush » tout ce qui a été persisté avant
    $em->flush();

        // Reste de la méthode qu'on avait déjà écrit
        if ($request->isMethod('POST')) {
          $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

          // Puis on redirige vers la page de visualisation de cettte annonce
          return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
        }

    // Si on n'est pas en POST, alors on affiche le formulaire
    return $this->render('OCPlatformBundle:Advert:add.html.twig', array('advert' => $advert));
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