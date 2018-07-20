<?php

namespace App\Controller;


use App\Entity\Annonce;
use App\Form\AnnonceFormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AnnonceController extends Controller
{
    /**
     * @Route("/annonceAdd", name="annonceAdd")
     */
    public function add(Request $request){

        $annonce = new Annonce();

        $form = $this->createForm(AnnonceFormType::class , $annonce)->add('Ajouter' , SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute("/annonceShow");

        }

        return $this->render('annonce/annonceAdd.html.twig' ,
            ["form" => $form->createView()]);

    }
    /**
     * @Route("/annonceShow", name="annonceShow")
     */

    public function show(){

        return $this->render('annonce/annonceShow.html.twig');
    }



}
