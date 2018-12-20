<?php
namespace App\Tests\Services;

use App\Entity\Ticket;
use App\Services\TicketServices;

use PHPUnit\Framework\TestCase;

class TicketServicesTest extends TestCase
{
    /**
     * @covers \App\Services\TicketServices::generatePriceType()
     * @covers \App\Entity\Ticket::getBirthDate
     * @covers \App\Entity\Ticket::getPriceType
     * @covers \App\Entity\Ticket::setBirthDate
     * @covers \App\Entity\Ticket::setPriceType
     */
    public function testGeneratePriceTypeToTwo()
    {
        $ticketServices = new TicketServices();
        $ticket = new Ticket();

        $ticket->setBirthDate(new \DateTime("2017-11-20"));
        $ticketServices->generatePriceType($ticket);

        $this->assertEquals($ticket::PRICE_TYPE_BABY, $ticket->getPriceType());
    }

    /**
     * @covers \App\Services\TicketServices::generatePriceType()
     * @covers \App\Entity\Ticket::getBirthDate
     * @covers \App\Entity\Ticket::getPriceType
     * @covers \App\Entity\Ticket::setBirthDate
     * @covers \App\Entity\Ticket::setPriceType
     */
    public function testGeneratePriceTypeToThree()
    {
        $ticketServices = new TicketServices();
        $ticket = new Ticket();

        $ticket->setBirthDate(new \DateTime("2009-11-20"));
        $ticketServices->generatePriceType($ticket);

        $this->assertEquals($ticket::PRICE_TYPE_CHILD, $ticket->getPriceType());
    }

    /**
     * @covers \App\Services\TicketServices::generatePriceType()
     * @covers \App\Entity\Ticket::getBirthDate
     * @covers \App\Entity\Ticket::getPriceType
     * @covers \App\Entity\Ticket::setBirthDate
     * @covers \App\Entity\Ticket::setPriceType
     */
    public function testGeneratePriceTypeToFour()
    {
        $ticketServices = new TicketServices();
        $ticket = new Ticket();

        $ticket->setBirthDate(new \DateTime("1990-11-20"));
        $ticketServices->generatePriceType($ticket);

        $this->assertEquals($ticket::PRICE_TYPE_NORMAL, $ticket->getPriceType());
    }

    /**
     * @covers \App\Services\TicketServices::generatePriceType()
     * @covers \App\Entity\Ticket::getBirthDate
     * @covers \App\Entity\Ticket::getPriceType
     * @covers \App\Entity\Ticket::setBirthDate
     * @covers \App\Entity\Ticket::setPriceType
     */
    public function testGeneratePriceTypeToFive()
    {
        $ticketServices = new TicketServices();
        $ticket = new Ticket();
        $ticket->setBirthDate(new \DateTime("1930-11-20"));
        $ticketServices->generatePriceType($ticket);
        $this->assertEquals($ticket::PRICE_TYPE_SENIOR, $ticket->getPriceType());
    }

    /**
     * @covers \App\Services\TicketServices::generatePriceType()
     * @covers \App\Entity\Ticket::getBirthDate
     * @covers \App\Entity\Ticket::getPriceType
     * @covers \App\Entity\Ticket::setBirthDate
     * @covers \App\Entity\Ticket::setPriceType
     */
    public function testGeneratePriceTypeToOne()
    {
        $ticketServices = new TicketServices();
        $ticket = new Ticket();

        $ticket->setBirthDate(new \DateTime("1990-11-20"));
        $ticket->setPriceType(1);
        $ticketServices->generatePriceType($ticket);

        $this->assertEquals($ticket::PRICE_TYPE_REDUCTION, $ticket->getPriceType());
    }

    // Beginning of deductPrice tests

    /**
     * @covers \App\Services\TicketServices::deductPrice()
     * @covers \App\Entity\Ticket::getPriceType
     * @covers \App\Entity\Ticket::getVisitAt
     * @covers \App\Entity\Ticket::setPriceType
     * @covers \App\Entity\Ticket::setVisitAt
     */
    public function testDeductPriceReduction()
    {
        $ticketServices = new TicketServices();
        $ticket = new Ticket();
        $ticket->setPriceType($ticket::PRICE_TYPE_REDUCTION);
        $ticket->setVisitAt(new \DateTime("2018-11-30 12:00"));
        $ticketServices->deductPrice($ticket);
        $this->assertEquals(10, $ticketServices->deductPrice($ticket));
    }

    /**
     * @covers \App\Services\TicketServices::deductPrice()
     * @covers \App\Entity\Ticket::getPriceType
     * @covers \App\Entity\Ticket::getVisitAt
     * @covers \App\Entity\Ticket::setPriceType
     * @covers \App\Entity\Ticket::setVisitAt
     */
    public function testDeductPriceBaby()
    {
        $ticketServices = new TicketServices();
        $ticket = new Ticket();

        $ticket->setPriceType($ticket::PRICE_TYPE_BABY);
        $ticket->setVisitAt(new \DateTime("2018-11-30 12:00"));
        $ticketServices->deductPrice($ticket);

        $this->assertEquals(0, $ticketServices->deductPrice($ticket));
    }

    /**
     * @covers \App\Services\TicketServices::deductPrice()
     * @covers \App\Entity\Ticket::getPriceType
     * @covers \App\Entity\Ticket::getVisitAt
     * @covers \App\Entity\Ticket::setPriceType
     * @covers \App\Entity\Ticket::setVisitAt
     */
    public function testDeductPriceChild()
    {
        $ticketServices = new TicketServices();
        $ticket = new Ticket();

        $ticket->setPriceType($ticket::PRICE_TYPE_CHILD);
        $ticket->setVisitAt(new \DateTime("2018-11-30 12:00"));
        $ticketServices->deductPrice($ticket);

        $this->assertEquals(8, $ticketServices->deductPrice($ticket));
    }

    /**
     * @covers \App\Services\TicketServices::deductPrice()
     * @covers \App\Entity\Ticket::getPriceType
     * @covers \App\Entity\Ticket::getVisitAt
     * @covers \App\Entity\Ticket::setPriceType
     * @covers \App\Entity\Ticket::setVisitAt
     */
    public function testDeductPriceNormal()
    {
        $ticketServices = new TicketServices();
        $ticket = new Ticket();

        $ticket->setPriceType($ticket::PRICE_TYPE_NORMAL);
        $ticket->setVisitAt(new \DateTime("2018-11-30 12:00"));
        $ticketServices->deductPrice($ticket);

        $this->assertEquals(16, $ticketServices->deductPrice($ticket));
    }

    /**
     * @covers \App\Services\TicketServices::deductPrice()
     * @covers \App\Entity\Ticket::getPriceType
     * @covers \App\Entity\Ticket::getVisitAt
     * @covers \App\Entity\Ticket::setPriceType
     * @covers \App\Entity\Ticket::setVisitAt
     */
    public function testDeductPriceSenior()
    {
        $ticketServices = new TicketServices();
        $ticket = new Ticket();

        $ticket->setPriceType($ticket::PRICE_TYPE_SENIOR);
        $ticket->setVisitAt(new \DateTime("2018-11-30 12:00"));
        $ticketServices->deductPrice($ticket);

        $this->assertEquals(12, $ticketServices->deductPrice($ticket));
    }

    /**
     * @covers \App\Services\TicketServices::deductPrice()
     * @covers \App\Entity\Ticket::getPriceType
     * @covers \App\Entity\Ticket::getVisitAt
     * @covers \App\Entity\Ticket::setPriceType
     * @covers \App\Entity\Ticket::setVisitAt
     */
    public function testDeductPriceNormalHalfDay()
    {
        $ticketServices = new TicketServices();
        $ticket = new Ticket();

        $ticket->setPriceType($ticket::PRICE_TYPE_NORMAL);
        $ticket->setVisitAt(new \DateTime("2018-11-30 16:00"));
        $ticketServices->deductPrice($ticket);

        $this->assertEquals(8, $ticketServices->deductPrice($ticket));
    }
}