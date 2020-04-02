<?php

namespace App\Service;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailSender
{
    private $mailer;
    private $environment;

    public function __construct(\Swift_Mailer $mailer, Environment $environment)
    {
        $this->mailer = $mailer;

        return $this->environment = $environment;
    }

    public function sendMessage($user, $view, $email)
    {
        try {
            $message = (new \Swift_Message('Slave Migrations'))
                ->setFrom('ghost_of_social_network@mail.com')
                ->setTo($email)
                ->setBody(
                    $this->environment->render(
                        $view,
                        [
                            'user' => $user,
                        ]
                    ),
                    'text/html'
                );
            $this->mailer->send($message);
        } catch (LoaderError $e) {
        } catch (RuntimeError $e) {
        } catch (SyntaxError $e) {
        }
    }
}
