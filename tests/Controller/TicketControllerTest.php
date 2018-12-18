<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketControllerTest extends WebTestCase
{

    public function testCreateTicket()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/ticket/create');

        $form = $crawler->selectButton('Enregistrer')->form();

// set some values
        $form['name'] = 'Lucas';
        $form['firstName'] = 'Bono';
        $form['birthDate'] = new \DateTime("2009-10-23");
        $form['country'] = 'France';
        $form['visitAt'] = new \DateTime("2019-02-24");
        $form['priceType'] = 4;

// submit the form
        $crawler = $client->submit($form);

        $client->followRedirect();

        dump($client->getResponse()->getContent());
    }

}