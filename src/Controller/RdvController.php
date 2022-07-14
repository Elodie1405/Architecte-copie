<?php

namespace App\Controller;

use App\Entity\Rdv;
use App\Form\RdvType;
use App\Entity\Disponibility;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DisponibilityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RdvController extends AbstractController
{

public function __construct(EntityManagerInterface $entityManager)
{
    $this->entityManager = $entityManager;
}


    /**
     * @Route("/account/rdv/", name="list_rdv")
     */
    public function listRdv(Request $request): Response
    {
        $dispos = $this->entityManager->getRepository(Disponibility::class)->findAll();

        
                return $this->render('rdv/list_rdv.html.twig',[
                    'dispos' => $dispos,
        ]);

    } 

    /**
     * @Route("/account/rdv/{id}", name="add_rdv", methods={"GET|POST"})
     */
    public function addRdv($id, Request $request): Response
    {
        $dispo = $this->entityManager->getRepository(Disponibility::class)->find($id);

        $rdv = new Rdv();
        $form = $this->createForm(RdvType::class, $rdv);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            
                $rdv->setCustomer($this->getUser());
                $rdv->setDispo($dispo);
                $dispo->setReserved(true);
                //dd($rdv);
        
                $this->entityManager->persist($rdv);
                $this->entityManager->flush();
                $this->addFlash('success', "Votre RDV a bien été enregistré, merci à vous ! ");
                return $this->redirectToRoute('account');
                
            }
                return $this->render('rdv/rdv.html.twig',[
                    'rdvForm' => $form->createView(),
                    'id' => $dispo->getId(),
                    'date' =>$dispo->getStart(),
                    
        ]);

    } 

    


}