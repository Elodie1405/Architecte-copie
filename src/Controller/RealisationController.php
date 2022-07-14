<?php

namespace App\Controller;

use App\Entity\Realisations;
use App\Form\RealisationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class RealisationController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("realisations", name="realisations")
     */
    public function realisations(): Response
    {
        $realisations = $this->entityManager->getRepository(Realisations::class)->findAll();

        return $this->render('realisation/realisations.html.twig', [
            'realisations' => $realisations,
        ]);
    }

    /**
     * @Route("admin/realisations", name="add_realisations")
     */
    public function add_realisation(Request $request, SluggerInterface $slugger): Response
    {
        $realisation = new Realisations();
        $form = $this->createForm(RealisationType::class, $realisation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $realisation = $form->getData();
            $realisation->setCreatedAt(new \DateTime());

            $file = $form->get('picture')->getData();
            
            if ($file) {
                $extension = '.' . $file->guessExtension();
                $safeFilename = $slugger->slug($realisation->gettitle());
                $newFilename = $safeFilename . '_' . '1' . $extension;
                try {
                    $file->move($this->getParameter('projets_dir'), $newFilename);
                    $realisation->setPicture($newFilename);
                } catch (FileException $exception) {
                }
            }

        $this->entityManager->persist($realisation);   
        $this->entityManager->flush();
        $this->addFlash('success', 'Projet réalisé créé !');
        return $this->redirectToRoute('list-real');
    }
        return $this->render('realisation/realisationsForm.html.twig', [
            'realisationForm' => $form->createView(),
            ]);
    }
}
