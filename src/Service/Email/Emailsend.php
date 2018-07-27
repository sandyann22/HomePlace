<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 26/07/2018
 * Time: 14:22
 */

namespace App\Service\Email;


use App\Entity\User;
/**
 * @var \Swift_Mailer
 */


/**
 * @var \Twig_Environment templating
 */

class Emailsend
{

    private $templating;
    private $mailer;
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->mailer     = $mailer;
        $this->templating = $templating;
    }
    public function sendWelcomeMail(User $user)
    {

            $message = (new \Swift_Message('Welcome Email'))
                ->setFrom('sandycerdan51@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->templating->render(
                    // templates/emails/registration.html.twig
                        'home/emailbienvenue.html.twig',
                        array('user' => $user)
                    ),
                    'text/html'
                );

        $this->mailer->send($message);
    }

}