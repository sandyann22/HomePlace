<?php

namespace App\Controller;

use App\Form\UserFormType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{
    /**
     * @Route("/inscription", name="security_registration")
     */
   public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
   {
       $user = new User();

       $form = $this->createForm(UserFormType::class, $user);

       $form->handleRequest($request);                                      //RECUPERATION ET ANALYSE DE LA REQUETE
       if ($form->isSubmitted() && $form->isValid()) {                        //SI LE FORM ES SOUMIS ET VALIDE
           $hash = $encoder->encodePassword($user, $user->getPassword());
           //encode le password qui est dans user avec la méthode getPassword

           $user->setPassword($hash);                                         //REINITIALISE AVEC LE CRYPTAGE
           $manager->persist($user);                                          //INSCRIT LE NEW USER DANS LA BDD
           $manager->flush();                                                 //ENREGISTRE LE
           return $this->redirectToRoute('security_login');
       }
       return $this->render('security/registration.html.twig', ['form' => $form->createView()
       ]);
   }
       /**
        * @Route("/connexion", name="security_login")
        */

       public function login()
       {
           return $this->render('security/login.html.twig');
       }

    public function loadUserByUsername($username){
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }


}
