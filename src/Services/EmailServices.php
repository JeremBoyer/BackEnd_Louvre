<?php
namespace App\Services;

use \NotFloran\MjmlBundle\Mjml;


class EmailServices
{
    /**
     * @var
     */

    /**
     * @var Mjml
     */
    private $mjml;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct()
    {

    }

    public function sendEmail($sessionTickets, $ticketServices, $command, \Twig_Environment $twig, Mjml $mjml)
    {
        $message = (new \Swift_Message('Louvre : Confirmation de commande'))
            ->setFrom('jereboyer08@gmail.com')
            ->setTo($_POST['stripeEmail'])
            ->setBody(
                $this->$mjml->render(
                    $this->$twig->render('email/email.mjml.twig', [
                        'tickets' => $sessionTickets,
                        'price' => $ticketServices,
                        'command' => $command
                    ])
                ),
                'text/html'
            )
        ;

        $this->get('mailer')->send($message);
    }

}