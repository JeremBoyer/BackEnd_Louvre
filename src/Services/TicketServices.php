<?php

namespace App\Services;


use App\Entity\Ticket;

use App\Repository\TicketRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ManagerRegistry;


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
        $today = new  \DateTime();

        if ($ticket->getVisitAt()->format("j:m:Y") === $today->format("j:m:Y") && $today->format("H") >= 14 )
        {
            $ticket->setType(2);
        }
    }

}