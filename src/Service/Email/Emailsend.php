<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 26/07/2018
 * Time: 14:22
 */

namespace App\Service\Email;


use App\Entity\User;

class Emailsend
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig_Environment;

    public function index(User $user, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message ('Hello Email'))
            ->setFrom('sandycerdangras@orange.fr')
            ->setTo(($user->getEmail())
                ->setBody(
                    $this->renderView('home/emailbienvenue.html.twig',
                        array('username' => $user->getUsername())
                    ), 'text/html'
                ) );

        $this->mailer->send($message);
        return $this->render('accueil.html.twig');
    }

}