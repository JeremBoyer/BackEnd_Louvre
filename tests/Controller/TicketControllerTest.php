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
        $form['ticket[name]'] = 'Lucas';
        $form['ticket[firstName]'] = 'Bono';
        $form['ticket[birthDate]'] = "2009-10-23";
        $form['ticket[country]'] = 'FR';
        $form['ticket[visitAt]'] = "2019-02-24";
        $form['ticket[priceType]'] = 1;

// submit the form
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());

        dump($client->getResponse()->getContent());
    }

}