<?php

namespace App\Controller;

use App\Form\StripeType;
use App\Services\OrderServices;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrderController extends Controller
{
    /**
     * @Route("/order", name="order")
     */
    public function index(OrderServices $orderServices, Request $request)
    {
//
// Token is created using Checkout or Elements!
// Get the payment token ID submitted by the form:
//        \Stripe\Stripe::setApiKey("sk_test_dyef7XjJglQ11UrUYuYDgEr8");
//
//        $token = $_POST['stripeToken'];
//        $charge = \Stripe\Charge::create(array(
//            "amount" => 2000,
//            "currency" => "eur",
//            "source" => $token, // obtained with Stripe.js
//            "description" => "paiment test"
//        ));

//        $token  = $_POST['stripeToken'];
//        $email  = $_POST['stripeEmail'];
//
//        $customer = \Stripe\Customer::create(array(
//            'email' => $email,
//            'source'  => $token
//        ));
//
//        $charge = \Stripe\Charge::create(array(
//            'customer' => $customer->id,
//            'amount'   => 5000,
//            'currency' => 'eur'
//        ));
//
//
//        dump($request, $charge, $customer);
        if (!empty($_POST['stripeToken'])) {

            $orderServices->stripe();
        }


        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }

    /**
     * @Route("/charge", name="charge")
     */
    public function charge(Request $request)
    {

        return $this->render('order/index.html.twig');
    }
}
