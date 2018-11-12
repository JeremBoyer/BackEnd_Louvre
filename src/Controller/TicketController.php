<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Services\TicketServices;

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
     * @Route("/ticket/create", name="create_ticket")
     */
    public function create(Request $request, TicketServices $ticketServices, SessionInterface $session)
    {
        $ticket = new Ticket();

        $form = $this->createForm(TicketType::class, $ticket);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $ticketServices->generatePriceType($ticket);

            $sessionTickets = $session->get('tickets');

            $sessionTickets[uniqid()] = $ticket;


            $session->set('tickets', $sessionTickets);

            $this->addFlash(
                'success',
                'Vous avez ajouté un ticket!'
            );

            return $this->redirectToRoute('command');
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

        $this->addFlash(
            "info",
            "Votre commande a bien été abandonnée."
        );

        return $this->redirectToRoute('command');
    }

    /**
     * @Route("/ticket/delete/{ticketNumber}", name="delete_ticket")
     */
    public function delete(Session $session, $ticketNumber)
    {
        if (array_key_exists($ticketNumber, $session->get('tickets'))) {
            $tickets = $session->get('tickets');
            unset($tickets[$ticketNumber]);

            $session->set('tickets', $tickets);

            $this->addFlash(
                'info',
                'Vous avez supprimer le ticket'
            );
        }

        return $this->redirectToRoute('command');
    }
}
