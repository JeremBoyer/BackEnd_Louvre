<?php
namespace App\Services;

use App\Entity\Ticket;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionService
{
    public function storageTicket(Ticket $ticket)
    {
        $_SESSION['tickets'][] = array($ticket);

        $ticketBag = $_SESSION['tickets'];

        return $ticketBag;
    }
}