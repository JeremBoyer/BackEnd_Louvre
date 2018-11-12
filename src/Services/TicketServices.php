<?php

namespace App\Services;

use App\Entity\Ticket;
use App\Repository\TicketRepository;


class TicketServices
{
    /**
     * Generate ticket price type from date of birth or reduction
     *
     * @param Ticket $ticket
     * @return Ticket
     */
    public function generatePriceType(Ticket $ticket)
    {
        $today = new \DateTime();

        $diff = $today->diff($ticket->getBirthDate());

        if ($diff->y < 4){
            return $ticket->setPriceType($ticket::PRICE_TYPE_BABY);
        } elseif (4 <= $diff->y && $diff->y < 12) {
            return $ticket->setPriceType($ticket::PRICE_TYPE_CHILD);
        } elseif (12 <= $diff->y && $diff->y < 60) {
            // Not return because, visitor can have a discount card
            $ticket->setPriceType($ticket::PRICE_TYPE_NORMAL);
        } elseif ($diff->y >= 60) {
            // Not return because, visitor can have a discount card
            $ticket->setPriceType($ticket::PRICE_TYPE_SENIOR);
        }

        if ($ticket->getPriceType() === 1) {
            return $ticket->setPriceType($ticket::PRICE_TYPE_REDUCTION);
        }
    }

    /**
     * Return price according ticket price type and ticket visit date
     *
     * @param Ticket $ticket
     * @return float|int
     */
    public function deductPrice(Ticket $ticket)
    {
        if ($ticket->getPriceType() == Ticket::PRICE_TYPE_REDUCTION) {
            $price = 10;
        } elseif ($ticket->getPriceType() == Ticket::PRICE_TYPE_BABY) {
            $price = 0;
        } elseif ($ticket->getPriceType() == Ticket::PRICE_TYPE_CHILD) {
            $price = 8;
        } elseif ($ticket->getPriceType() == Ticket::PRICE_TYPE_NORMAL) {
            $price = 16;
        } elseif ($ticket->getPriceType() == Ticket::PRICE_TYPE_SENIOR) {
            $price = 12;
        }

        if ($ticket->getVisitAt()->format("H") >= 14) {
            $price = $price/2;
        }

        return $price;
    }

}