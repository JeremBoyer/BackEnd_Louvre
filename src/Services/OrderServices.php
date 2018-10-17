<?php

namespace App\Services;


use Stripe\StripeTest;
use Stripe\Error\Card;

class OrderServices
{
    public function price()
    {

    }

    public function stripe($total)
    {

        try {
            // Use Stripe's library to make requests...
            \Stripe\Stripe::setApiKey("sk_test_dyef7XjJglQ11UrUYuYDgEr8");

            $token  = $_POST['stripeToken'];
            $email  = $_POST['stripeEmail'];

            $customer = \Stripe\Customer::create(array(
                'email' => $email,
                'source'  => $token
            ));

            $charge = \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount'   => $total,
                'currency' => 'eur'
            ));
            dump($charge);


        } catch(\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $err  = $body['error'];

            print('Status is:' . $e->getHttpStatus() . "\n");
            print('Type is:' . $err['type'] . "\n");
            print('Code is:' . $err['code'] . "\n");
            // param is '' in this case
            print('Param is:' . $err['param'] . "\n");
            print('Message is:' . $err['message'] . "\n");
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
        }


        dump($charge, $customer);
    }

}