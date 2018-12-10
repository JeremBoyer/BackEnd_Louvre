<?php
namespace App\Tests\Services;

use App\Entity\Command;
use App\Services\CommandServices;
use App\Services\TicketServices;

use PHPUnit\Framework\TestCase;

use Psr\Log\LoggerInterface;
use Stripe\ApiOperations\Create;
use Stripe\Charge;
use Stripe\Customer;

class CommandServicesTest extends TestCase
{
    public function testStripeWillCreateCustomer()
    {
        $customerObject = new \stdClass();
        $customerObject->id = 0000;

        $mockCommandService = $this->getMockBuilder(CommandServices::class)
            ->setMethods([
                "createNewStripeCustomer",
                "createNewStripeCharge"
            ])
            ->getMock();

        $mockCommandService
            ->expects($this->once())
            ->method("createNewStripeCustomer")
            ->willReturn($customerObject);

        $mockLogger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $mockLogger
            ->expects($this->once())
            ->method('info')
            ->with("Creating charge and customer is a success");

        $this->assertTrue($mockCommandService->stripe(1000, $mockLogger));
    }

    public function testStripeWillCreateCharge()
    {
        $customerObject = new \stdClass();
        $customerObject->id = 0000;

        $chargeObject = new \stdClass();

        $mockCommandService = $this->getMockBuilder(CommandServices::class)
            ->setMethods([
                "createNewStripeCustomer",
                "createNewStripeCharge"
            ])
            ->getMock();

        $mockCommandService
            ->expects($this->once())
            ->method("createNewStripeCharge")
            ->willReturn($chargeObject);

        $mockLogger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $mockLogger
            ->expects($this->once())
            ->method('info')
            ->with("Creating charge and customer is a success");

        $this->assertTrue($mockCommandService->stripe(1000, $mockLogger));
    }

    public function testStripeWillCreateCharge()
    {
        $customerObject = new \stdClass();
        $customerObject->id = 0000;

        $chargeObject = new \stdClass();

        $mockCommandService = $this->getMockBuilder(CommandServices::class)
            ->setMethods([
                "createNewStripeCustomer",
                "createNewStripeCharge"
            ])
            ->getMock();

        $mockCommandService
            ->expects($this->once())
            ->method("createNewStripeCharge")
            ->will($this->throwException );

        $mockLogger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $mockLogger
            ->expects($this->once())
            ->method('info')
            ->with("Creating charge and customer is a success");

        $this->assertTrue($mockCommandService->stripe(1000, $mockLogger));
    }


}