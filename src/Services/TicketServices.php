<?php

namespace App\Services;


use App\Entity\Ticket;

class TicketServices
{

    public function priceType(Ticket $ticket)
    {
        $today = new \DateTime();

        $diff = $today->diff($ticket->getBirthDate());

        if ($ticket->getPriceType() === 0) {
            if ($diff->y < 4){
                $ticket->setPriceType(2);
            } elseif (4 <= $diff->y && $diff->y < 12) {
                $ticket->setPriceType(3);
            } elseif (12 <= $diff->y && $diff->y < 60) {
                $ticket->setPriceType(4);
            } elseif ($diff->y >= 60) {
                $ticket->setPriceType(5);
            }
        }
    }

}