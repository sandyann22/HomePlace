<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
//    /**
//     * @Route("/home", name="home")
//     */
//    public function index()
//    {
//        return $this->render('home/index.html.twig', [
//            'controller_name' => 'HomeController',
//        ]);
//    }

    /**
     * @Route("/", name="accueil")
     */

    public function home()
    {
        return $this->render('home/accueil.html.twig');
    }

    /**
     * @Route("/about", name="about")
     */

    public function about()
    {

        return $this->render('home/about.html.twig');
    }

//

}




