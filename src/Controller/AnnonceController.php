<?php

namespace App\Controller;


use App\Entity\Annonce;
use App\Form\AnnonceFormType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class AnnonceController extends Controller
{
    /**
     * @Route("/annonceadd", name="annonceadd")
     */
    public function add(Request $request, EntityManagerInterface $em, FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $annonce = new Annonce();
        $form = $this->createForm(AnnonceFormType::class, $annonce)
            ->add("image", fileType::class,  array(
                'label' => 'Image (JPEG file)',
                'data_class' => null
            ))
            ->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $file = $annonce->getImage();
            $fileName = $fileUploader->upload($file);
            $annonce->setImage($fileName);

            $em->persist($annonce);
            $em->flush();


            return $this->redirectToRoute("annonceup");

        }

        return $this->render('annonce/annonceAdd.html.twig',
            [
                "form" => $form->createView(),
             'annonces' => $annonce

            ]);


    }

    /**
     * @Route("/annonceshow/{id}", name="annonceshow")
     *
     */

    public function show(Annonce $annonce)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('annonce/annonceShow.html.twig', [

            'annonce' => $annonce
        ]);

    }
    //MODIFIER UNE ANNONCE
    /**
     * @Route("/annonceup",name="annonceup")
     */
    public function annonceUp()
    {

        $em = $this->getDoctrine()->getManager();
        $annonce = $em->getRepository(Annonce::class)->findAll();

        return $this->render('home/annonceUpToDate.html.twig', [

            'annonces' => $annonce
        ]);
    }
    //MODIFIER UNE ANNONCE
    /**
     * @Route("/annonce/update/{annonce}", name="updateann")
     */
    public function update_ann(Request $request, EntityManagerInterface $em ,  Annonce $annonce)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(AnnonceFormType::class, $annonce)
            ->add('Update', SubmitType::class)
            ->add("image", fileType::class,  array(
                'label' => 'Image (JPEG file)',
                'data_class' => null
            ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($annonce);
            $em->flush();

            return $this->redirectToRoute("annonceup");
        }

        return $this->render('annonce/update_ann.html.twig',
            ["form" => $form->createView()]);

    }

    //SUPPRIMER UNE ANNONCE

    /**
     * @Route("/annonce/delete/{annonce}" , name="annoncedelete")
     */
    public function delete_ann(Annonce $annonce)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();
        return $this->redirectToRoute("annonceup");
    }


    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }


}
