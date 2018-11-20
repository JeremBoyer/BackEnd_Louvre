<?php
namespace App\Tests\Services;

use App\Entity\Ticket;
use App\Services\TicketServices;
use PHPUnit\Framework\TestCase;

class TicketServicesTest extends TestCase
{

    public function testGeneratePriceType()
    {

        $ticketService = $this->getMockBuilder(TicketServices::class)->getMock();
        $ticket = new Ticket();

        $ticket->setBirthDate(new \DateTime("2017-11-20"));
        
//        $this->getResult($ticketService->generatePriceType($ticket));
        $this->assertEquals($ticket::PRICE_TYPE_BABY, $ticket->getPriceType());
    }
}