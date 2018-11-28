<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\StripeType;
use App\Services\CommandServices;
use App\Services\EmailServices;
use App\Services\TicketServices;
use App\Entity\Command;
use App\Services\MjmlServices;
use NotFloran\MjmlBundle\Mjml;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommandController extends Controller
{
    /**
     * @Route("/command", name="command")
     */
    public function command(
        TicketServices $ticketServices,
        SessionInterface $session,
        CommandServices $commandServices,
        LoggerInterface $logger
    )
    {
        $total = 0;
        $numberOfTickets = 0;

        if (!empty($session->get('tickets'))) {
            $sessionTickets = $session->get('tickets');

            foreach ($sessionTickets as $ticket) {
                $numberOfTickets++ ;
                $total = $ticketServices->deductPrice($ticket) + $total;
                dump($sessionTickets, $ticket);
            }
        }

        if (!empty($_POST['stripeToken'])) {
            $payment = $commandServices->stripe($total, $logger);
            if ($payment === false) {
                $this->addFlash(
                    "danger",
                    "Une erreur s'est produit lors du paiement veuillez réessayer et/ou contacez l'administrateur"
                );
            } elseif ($payment === true) {
                $command = new Command();

                $command->setEmail($_POST['stripeEmail']);
                $command->setNumberOfTicket($numberOfTickets);
                $command->setTotalPrice($total);
                $command->setCommandAt(new \DateTime());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($command);

                $sessionTickets = $session->get('tickets');
                foreach ($sessionTickets as $ticket) {
                    $ticket->setCommand($command);
                    $entityManager->persist($ticket);
                    $command->addTicket($ticket);
                }

                $entityManager->flush();

                return $this->forward("App\Controller\CommandController::confirmation", array(
                    "command"       => $command,
                    "ticketService" => $ticketServices,
                ));
            }

        }

        return $this->render('command/command.html.twig', [
            'controller_name' => 'CommandController',
            'ticketService' => $ticketServices,
            'total' => $total,
            'numberOfTickets' => $numberOfTickets
        ]);
    }

    /**
     * @Route("/command/send", name="send")
     */
    public function confirmation(TicketServices $ticketService, Command $command)
    {
        $message = (new \Swift_Message('Louvre : Confirmation de commande'))
            ->setFrom('jereboyer08@gmail.com')
            ->setTo($_POST['stripeEmail'])
            ->setBody(
                $this->get('mjml')->render(
                    $this->get('twig')->render('email/email.mjml.twig', [
                        'tickets' => $command->getTickets(),
                        'ticketService' => $ticketService,
                        'command' => $command
                    ])
                ),
                'text/html'
            );

        $this->get('mailer')->send($message);

        return $this->render('command/confirmation.html.twig');
    }
}
