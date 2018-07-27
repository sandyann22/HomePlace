<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Service\Email\Emailsend;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/inscription", name="security_registration")
     */
   public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, Emailsend $emailsend)
   {
       $user = new User();

       $form = $this->createForm(UserFormType::class, $user);

       $form->handleRequest($request);                                      //RECUPERATION ET ANALYSE DE LA REQUETE
       if ($form->isSubmitted() && $form->isValid()) {                        //SI LE FORM ES SOUMIS ET VALIDE
           $hash = $encoder->encodePassword($user, $user->getPassword());
           //encode le password qui est dans user avec la méthode getPassword
           //REINITIALISE AVEC LE CRYPTAGE=
           $user->setPassword($hash);
           $emailsend->sendWelcomeMail($user);

           $user->addRole('ROLE_USER');

           $manager->persist($user);                                          //INSCRIT LE NEW USER DANS LA BDD
           $manager->flush();                                                 //ENREGISTRE LE


           return $this->redirectToRoute('security_login');

       }
       return $this->render('security/registration.html.twig', ['form' => $form->createView()
       ]);
   }

       /**
        * @Route("/login", name="security_login")
        */

       public function login(Request $request, AuthenticationUtils $authenticationUtils)
       {
           // get the login error if there is one
           $error = $authenticationUtils->getLastAuthenticationError();
            dump($error);
           // last username entered by the user
           $lastUsername = $authenticationUtils->getLastUsername();

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
    /**
     * @Route("/logout", name="logout")
     */
public function logout(){
//return $this->render('security/login.html.twig');
}

}
