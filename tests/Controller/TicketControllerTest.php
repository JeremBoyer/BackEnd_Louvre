<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketControllerTest extends WebTestCase
{

    /**
     * @covers \App\Controller\TicketController::create()
     * @covers \App\Entity\Ticket::getBirthDate
     * @covers \App\Entity\Ticket::getCountry
     * @covers \App\Entity\Ticket::getFirstName
     * @covers \App\Entity\Ticket::getName
     * @covers \App\Entity\Ticket::getPriceType
     * @covers \App\Entity\Ticket::getVisitAt
     * @covers \App\Entity\Ticket::setBirthDate
     * @covers \App\Entity\Ticket::setCountry
     * @covers \App\Entity\Ticket::setFirstName
     * @covers \App\Entity\Ticket::setName
     * @covers \App\Entity\Ticket::setPriceType
     * @covers \App\Entity\Ticket::setVisitAt
     * @covers \App\Form\Extension\FlatpickrBirthdayTypeExtension::configureOptions
     * @covers \App\Form\Extension\FlatpickrBirthdayTypeExtension::getExtendedType
     * @covers \App\Form\Extension\FlatpickrDateTimeTypeExtension::configureOptions
     * @covers \App\Form\Extension\FlatpickrDateTimeTypeExtension::getExtendedType
     * @covers \App\Form\TicketType::buildForm
     * @covers \App\Form\TicketType::configureOptions
     * @covers \App\Kernel::getCacheDir
     * @covers \App\Kernel::registerBundles
     * @covers \App\Repository\TicketRepository::__construct
     * @covers \App\Validator\Constraints\OpeningDateValidator::validate
     * @covers \App\Validator\Constraints\ThousandLimitValidator::__construct
     * @covers \App\Validator\Constraints\ThousandLimitValidator::validate
     */
    public function testCreateTicket()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/ticket/create');

        $form = $crawler->selectButton('Enregistrer')->form();



// set some values
        $form['ticket[name]'] = 'Lucas';
        $form['ticket[firstName]'] = 'Bono';
        $form['ticket[birthDate]'] = "02-05-1989";
        $form['ticket[country]'] = 'FR';
        $form['ticket[visitAt]'] = "25-02-2019 12:00:00";
        $form['ticket[priceType]'] = 1;

// submit the form
        $crawler = $client->submit($form);
        dump($client->getResponse()->isOk(), $client->getResponse()->isRedirect("/command"));

        $this->assertTrue($client->getResponse()->isRedirect("/command"), "c'est ok");

        dump($client->getResponse()->isOk());
    }

}