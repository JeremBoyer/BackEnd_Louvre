<?php

namespace App\Controller;

use App\Form\StripeType;
use App\Services\CommandServices;
use App\Services\TicketServices;
use App\Entity\Command;
use App\Services\MjmlServices;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommandController extends Controller
{
    /**
     * @Route("/command", name="command")
     */
    public function command(Request $request, TicketServices $ticketServices, SessionInterface $session, CommandServices $commandServices)
    {
//        dump($ticketServices->countTickets($this->getDoctrine()->getRepository(Ticket::class)));
        dump($session->get('tickets'));
        $total = 0;
        $numberOfTickets = 0;

        if (!empty($session->get('tickets'))) {
            $sessionTickets = $session->get('tickets');

            foreach ($sessionTickets as $ticket) {
                $numberOfTickets++ ;
                $total = $ticketServices->price($ticket) + $total;
            }
        }

        if (!empty($_POST['stripeToken'])) {

            $commandServices->stripe($total);

            $command = new Command();

            dump($commandServices->stripe($total));

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
                dump($ticket);
            }
//
            $entityManager->flush();

            $message = (new \Swift_Message('Louvre : Confirmation de commande'))
                ->setFrom('jereboyer08@gmail.com')
                ->setTo($_POST['stripeEmail'])
                ->setBody(
                    $this->get('mjml')->render(
                        $this->get('twig')->render('email/email.mjml.twig', [
                            'tickets' => $sessionTickets,
                            'price' => $ticketServices,
                            'command' => $command
                        ])
                    ),
                    'text/html'
                )
//            ->setBody(
//                $this->renderView('email/email.html.twig', [
//                    'tickets' => $sessionTickets,
//                    'command' => $command
//                    ])
//                )
            ;

            $this->get('mailer')->send($message);
//
            return $this->redirectToRoute('confirmation');
        }



        return $this->render('command/command.html.twig', [
            'controller_name' => 'CommandController',
            'price' => $ticketServices,
            'total' => $total,
            'numberOfTickets' => $numberOfTickets
        ]);
    }

    /**
     * @Route("/command/confirmation", name="confirmation")
     */
    public function confirmation(Request $request)
    {
        dump($request);
//        $message = (new \Swift_Message('Hello Email'))
//            ->setFrom('jereboyer08@gmail.com')
//            ->setTo('jereboyer08@gmail.com')
//            ->setBody(
//                $this->get('twig')->render('email/email.mjml.twig', [
//                    'name' => 'Floran'
//                ]),
//                'text/html'
//            )
//        ;
//
//        $this->get('mailer')->send($message);

        return $this->render('command/confirmation.html.twig');
    }
}
