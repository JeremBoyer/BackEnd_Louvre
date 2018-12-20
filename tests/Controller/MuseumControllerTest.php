<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HTTPFoundation\Response;

class MuseumControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @covers \App\Controller\MuseumController::home()
     * @covers \App\Kernel::getCacheDir
     * @covers \App\Kernel::registerBundles
     */
    public function testHomepageIsUp()
    {
        $this->client->request('GET', '/');

        static::assertEquals(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
    }

    /**
     * @covers \App\Controller\MuseumController::useful()
     * @covers \App\Kernel::getCacheDir
     * @covers \App\Kernel::registerBundles
     */
    public function testUsefulIsUp()
    {
        $this->client->request('GET', '/useful');

        static::assertEquals(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
    }
}