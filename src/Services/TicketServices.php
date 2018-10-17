<?php

namespace App\Services;

use App\Entity\Ticket;
use App\Repository\TicketRepository;


class TicketServices
{

    public function priceType(Ticket $ticket)
    {
        $today = new \DateTime();

        $diff = $today->diff($ticket->getBirthDate());

        if ($ticket->getPriceType() === 1) {
            return $ticket->setPriceType($ticket::PRICE_TYPE_REDUCTION);
        }

        if ($diff->y < 4){
            $ticket->setPriceType($ticket::PRICE_TYPE_BABY);
        } elseif (4 <= $diff->y && $diff->y < 12) {
            $ticket->setPriceType($ticket::PRICE_TYPE_CHILD);
        } elseif (12 <= $diff->y && $diff->y < 60) {
            $ticket->setPriceType($ticket::PRICE_TYPE_NORMAL);
        } elseif ($diff->y >= 60) {
            $ticket->setPriceType($ticket::PRICE_TYPE_SENIOR);
        }
    }

    public function countTickets(TicketRepository $ticketRepo)
    {
        $countTickets = $ticketRepo->count([]);
        return $countTickets;
    }

    public function halfDay(Ticket $ticket)
    {
        if ($ticket->getVisitAt()->format("H") >= 14 )
        {
            $ticket->setType(2);
        }
    }

    public function price(Ticket $ticket)
    {
        if ($ticket->getPriceType() == Ticket::PRICE_TYPE_REDUCTION) {
            $price = 1000;
        } elseif ($ticket->getPriceType() == Ticket::PRICE_TYPE_BABY) {
            $price = 0;
        } elseif ($ticket->getPriceType() == Ticket::PRICE_TYPE_CHILD) {
            $price = 800;
        } elseif ($ticket->getPriceType() == Ticket::PRICE_TYPE_NORMAL) {
            $price = 1600;
        } elseif ($ticket->getPriceType() == Ticket::PRICE_TYPE_SENIOR) {
            $price = 1200;
        } else {
            echo "erreur";
        }

        if ($ticket->getVisitAt()->format("H") >= 14) {
            $price = $price/2;
        }

        return $price;
    }

}