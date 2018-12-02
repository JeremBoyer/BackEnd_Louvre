<?php

namespace App\Services;

use Stripe\Error\Card;
use Stripe\Error\RateLimit;
use Stripe\Error\Authentication;
use Stripe\Error\ApiConnection;
use Stripe\Error\Base;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;

use Psr\Log\LoggerInterface;


class CommandServices
{
    /**
     * Creating charge and customer with Stripe and manage error in back
     *
     * @param $total
     * @param LoggerInterface $logger
     * @return bool
     */
    public function stripe($total, LoggerInterface $logger)
    {

        try {
            // Use Stripe's library to make requests...
            Stripe::setApiKey("sk_test_dyef7XjJglQ11UrUYuYDgEr8");

            $customer = $this->createNewStripeCustomer();
            $this->createNewStripeCharge($total, $customer);

            $logger->info("Creating charge and customer is a success");
            return true;


        } catch(Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $err  = $body['error'];

            print('Status is:' . $e->getHttpStatus() . "\n");
            print('Type is:' . $err['type'] . "\n");
            print('Code is:' . $err['code'] . "\n");
            // param is '' in this case
            print('Param is:' . $err['param'] . "\n");
            print('Message is:' . $err['message'] . "\n");
            $logger->error("Stripe error : " . $e->getStripeCode() . "//" . $e->getMessage());
            return false;
        } catch (RateLimit $e) {
            $logger->error("Stripe error : " . $e->getStripeCode() . "//" . $e->getMessage());
            // Too many requests made to the API too quickly
            return false;
        } catch (InvalidRequest $e) {
            $logger->error("Stripe error : " . $e->getMessage());
            // Invalid parameters were supplied to Stripe's API
            return false;
        } catch (Authentication $e) {
            // Authentication with Stripe's API failed
            $logger->error("Stripe error : " . $e->getStripeCode() . "//" . $e->getMessage());
            // (maybe you changed API keys recently)
            return false;
        } catch (ApiConnection $e) {
            // Network communication with Stripe failed
            $logger->error("Stripe error : " . $e->getStripeCode() . "//" . $e->getMessage());
            dump($e);
            return false;
        } catch (Base $e) {
            $logger->error("Stripe error : " . $e->getStripeCode(). "//" . $e->getMessage());
            dump($e, $e->getStripeCode(), $logger);
            // Display a very generic error to the user, and maybe send
            // yourself an email
            return false;
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $logger->error("Stripe error : " . $e->getMessage());
            return false;
        }


    }

    public function createNewStripeCustomer()
    {
        dump('coucou');
        die();
        return Customer::create(array(
            'email' => $_POST['stripeEmail'],
            'source'  => $_POST['stripeToken']
        ));
    }

    public function createNewStripeCharge($total, $customer)
    {
        return Charge::create(array(
            'customer' => $customer->id,
            'amount'   => $total*100,
            'currency' => 'eur'
        ));
    }

}