<?php

namespace App\Controller;

use App\Entity\Command;
use App\Entity\Ticket;
use App\Form\TicketType;

use App\Repository\CommandRepository;
use App\Repository\TicketRepository;
use App\Services\CommandServices;
use App\Services\TicketServices;
use Doctrine\Common\Persistence\ObjectManager;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TicketController extends Controller
{
    /**
     * @Route("/ticket", name="ticket")
     */
    public function index()
    {
        return $this->render('ticket/index.html.twig', [
            'controller_name' => 'TicketController',
        ]);
    }

    /**
     * @Route("/ticket/summary", name="ticket_summary")
     */
    public function ticketSummary(Request $request, TicketServices $ticketServices, SessionInterface $session, CommandServices $commandServices)
    {
//        dump($ticketServices->countTickets($this->getDoctrine()->getRepository(Ticket::class)));
        dump($request);
        $total = 0;
        if (!empty($session->get('tickets'))) {
            $sessionTickets = $session->get('tickets');
            $numberOfTickets = 0;
            foreach ($sessionTickets as $ticket) {
                $numberOfTickets++ ;
                $total = $ticketServices->price($ticket) + $total;
            }
        }


        if (!empty($_POST['stripeToken'])) {

            $commandServices->stripe($total);

            $command = new Command();

            dump($commandServices->stripe($total));

//            dump($commandRepository->find(1));

            $command->setEmail($_POST['stripeEmail']);
            $command->setNumberOfTicket($numberOfTickets);
            $command->setTotalPrice($total);
            $command->setCommandAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($command);

//            dump($command);


            $sessionTickets = $session->get('tickets');
            foreach ($sessionTickets as $ticket) {
                $ticket->setCommand($command);
                $entityManager->persist($ticket);
                dump($ticket);
            }
//
            $entityManager->flush();


//
//            return $this->redirectToRoute('treatment');
        }




        return $this->render('ticket/summary.html.twig', [
            'price' => $ticketServices,
            'total' => $total,
            'numberOfTickets' => $numberOfTickets
        ]);
    }

    /**
     * @Route("/ticket/create", name="create_ticket")
     */
    public function create(Request $request, TicketServices $ticketServices, SessionInterface $session)
    {
        $ticket = new Ticket();

        $form = $this->createForm(TicketType::class, $ticket);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $ticketServices->priceType($ticket);
            $ticketServices->halfDay($ticket);


            $sessionTickets = $session->get('tickets');

            $sessionTickets[uniqid()] = $ticket;


            $session->set('tickets', $sessionTickets);

            $this->addFlash(
                'success',
                'Vous avez ajouté un ticket!'
            );

            return $this->redirectToRoute('ticket_summary');
        }

        return $this->render('ticket/create.html.twig', [
            'formTicket' => $form->createView()
        ]);
    }

    /**
     * @Route("/ticket/reset", name="reset_ticket")
     */
    public function reset(Session $session)
    {
        $session->clear();

        return $this->redirectToRoute('ticket_summary');
    }

    /**
     * @Route("/ticket/delete/{ticketNumber}", name="delete_ticket")
     */
    public function delete(Session $session, $ticketNumber)
    {
        dump($session->get('tickets')[$ticketNumber]);

        if (array_key_exists($ticketNumber, $session->get('tickets'))) {
            $tickets = $session->get('tickets');
            unset($tickets[$ticketNumber]);

            $session->set('tickets', $tickets);
        }

        return $this->redirectToRoute('ticket_summary');
    }

    /**
     * @Route("/ticket/treatment", name="treatment")
     */
    public function treatment()
    {
        if (!empty($charge = \Stripe\Charge::class)) {
            if ($charge->status === "succeeded");
            dump($charge);
        }
    }
}
