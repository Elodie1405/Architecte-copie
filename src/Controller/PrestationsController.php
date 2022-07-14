<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrestationsController extends AbstractController
{
    /**
     * @Route("/prestations", name="prestations")
     */
    public function index(): Response
    {
        return $this->render('prestations/prestations.html.twig');
    }
}
