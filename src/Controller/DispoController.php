<?php

namespace App\Controller;

use App\Form\DispoType;
use App\Entity\Disponibility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DispoController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("admin/dashboard/dispo", name="dispo")
     */
    public function dispo(Request $request): Response
    {

        $dispo = new Disponibility();
        $form = $this->createForm(DispoType::class, $dispo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
                $dispo = $form->getData();
                $dispo->setReserved(false);
                $this->entityManager->persist($dispo);
                $this->entityManager->flush();
                $this->addFlash('success', 'la plage horaire a été prise en compte !');
            }
                return $this->render('dispo/dispo.html.twig',[
                'dispoForm' => $form->createView(),
        ]);

    }
}
