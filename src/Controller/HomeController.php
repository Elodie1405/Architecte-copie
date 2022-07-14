<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('home/home.html.twig');
    }


     /**
     * @Route("/mentions_legales", name="mentions_legales")
     */
    public function mentionsLegales(): Response
    {
        return $this->render('home/mentions_legales.html.twig');
    }
}