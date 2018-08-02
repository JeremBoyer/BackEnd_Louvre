<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Ticket;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MuseumController extends Controller
{
    /**
     * @Route("/museum", name="museum")
     */
    public function index()
    {
        return $this->render('museum/index.html.twig', [
            'controller_name' => 'MuseumController',
        ]);
    }

    /**
     * @Route("/", name="home")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home()
    {
        return $this->render('museum/home.html.twig');
    }
}
