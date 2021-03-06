<?php
namespace App\Tests\Services;

use App\Services\CommandServices;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class CommandServicesTest extends TestCase
{
    /**
     * @covers \App\Services\CommandServices::createCustomerAndChargeStripe()
     * @covers \App\Kernel::configureContainer
     * @covers \App\Kernel::configureRoutes
     * @covers \App\Kernel::getLogDir
     * @covers \App\Kernel::getCacheDir
     * @covers \App\Kernel::registerBundles
     */
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

        $this->assertTrue($mockCommandService->createCustomerAndChargeStripe(1000, $mockLogger));
    }

    /**
     * @covers \App\Services\CommandServices::createCustomerAndChargeStripe()
     */
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
        $this->assertTrue($mockCommandService->createCustomerAndChargeStripe(1000, $mockLogger));
    }

    /**
     * @covers \App\Services\CommandServices::createCustomerAndChargeStripe()
     */
    public function testStripeWillNotCreateChargeAndThrowError()
    {
        $mockCommandService = $this->getMockBuilder(CommandServices::class)
            ->setMethods([
                "createNewStripeCustomer",
                "createNewStripeCharge"
            ])
            ->getMock();

        $mockCommandService
            ->expects($this->once())
            ->method("createNewStripeCharge")
            ->willThrowException(new \Exception("Error during creating charge"));

        $mockLogger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $mockLogger
            ->expects($this->once())
            ->method('error')
            ->with("Stripe error : Error during creating charge");

        $this->assertFalse($mockCommandService->createCustomerAndChargeStripe(1000, $mockLogger));
    }
}