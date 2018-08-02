<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;

use App\Services\SessionService;
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
    public function ticketSummary(Request $request, SessionService $sessionService)
    {
        //$ticketBags = $_SESSION['ticket'];

        //dump($_SESSION['ticket']);

        return $this->render('ticket/summary.html.twig');
    }

    /**
     * @Route("/ticket/create", name="create_ticket")
     */
    public function create(Request $request, ObjectManager $manager, SessionService $sessionService, Session $session)
    {
        $ticket = new Ticket();

        $form = $this->createForm(TicketType::class, $ticket);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$_SESSION['ticket'][] = array($ticket);
            $sessionTickets = $session->get('tickets');

            $sessionTickets[] = [
                uniqid() => $ticket
            ];

            $session->set('tickets', $sessionTickets);

            //dump($request, $_SESSION['ticket'], $ticket, $session);
            //die();

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


        return $this->redirectToRoute('ticket_summary');
    }

    /**
     * @Route("/ticket/delete", name="delete_ticket")
     */
    public function delete()
    {

    }
}
