<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;

use App\Services\SessionService;
use App\Services\TicketServices;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
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
    public function ticketSummary(Request $request)
    {
        $session = new Session();

        $test = $session->all();

        dump($test);
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

            $sessionTickets = $session->get('tickets');

            $sessionTickets[uniqid()] = $ticket;


            $session->set('tickets', $sessionTickets);

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
//        foreach ($session->get('tickets') as $key => $value) {
//            $session->get('tickets')[1]["5b7932f174532"];
//            dump($session->get('tickets')[1][$key]);
//            die();
//            if (key($value) === $request->attributes->get("key")) {
//                $session->remove();
//                unset($_SESSION['_sf2_attributes']['tickets'][$key]);
//                //$session->remove('tickets/' . $key);
//                //dump($_SESSION, $test1);
//            }
//        }

        return $this->redirectToRoute('ticket_summary');
    }
}
