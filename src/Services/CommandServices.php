<?php

namespace App\Services;


use Stripe\StripeTest;
use Stripe\Error\Card;
use Stripe\Error\RateLimit;
use Stripe\Error\Authentication;
use Stripe\Error\ApiConnection;
use Stripe\Error\Base;


class CommandServices
{

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


            return $charge;


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
            dump($err, $body, $e);
        } catch (RateLimit $e) {
            $e = new RateLimit("Limite de temps dépassé. Assurez vous d'avoir une bonne connexion et réessayez. En cas de nouvel échec contactez le musée");

            dump($e);
            // Too many requests made to the API too quickly
            return false;
        } catch (InvalidRequest $e) {
            $e = new RateLimit("Requete invalide");
            dump($e);
            // Invalid parameters were supplied to Stripe's API
            return false;
        } catch (Authentication $e) {
            // Authentication with Stripe's API failed
            dump($e);
            // (maybe you changed API keys recently)
            return false;
        } catch (ApiConnection $e) {
            // Network communication with Stripe failed
            dump($e);
            return false;
        } catch (Base $e) {
            dump($e);
            // Display a very generic error to the user, and maybe send
            // yourself an email
            return false;
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            dump($e);
            return false;
        }


    }

}