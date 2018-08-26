<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;

use App\Repository\TicketRepository;
use App\Services\TicketServices;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
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
    public function ticketSummary(Request $request, TicketServices $ticketServices)
    {
        dump($ticketServices->countTickets($this->getDoctrine()->getRepository(Ticket::class)));

        return $this->render('ticket/summary.html.twig');
    }

    /**
     * @Route("/ticket/create", name="create_ticket")
     */
    public function create(Request $request, TicketServices $ticketServices, Session $session)
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
}
