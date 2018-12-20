<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommandControllerTest extends WebTestCase
{

    /**
     * @covers \App\Controller\CommandController::command()
     * @covers \App\Kernel::getCacheDir
     * @covers \App\Kernel::registerBundles
     */
    public function testCommandToCreateTicket()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/command');

        self::assertSame(1, $crawler->filter('html:contains("Ajouter un ticket")')->count());
    }
}