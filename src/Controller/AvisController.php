<?php

namespace App\Controller;

use DateTime;
use App\Entity\Rdv;
use App\Entity\Avis;
use App\Form\AvisType;
use App\Entity\Realisations;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AvisController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/account/avis/{id}", name="avis", methods={"GET|POST"})
     * @param Request $request
     * @return Response
     * 
     */
    public function addAvis(Request $request): Response
    {

        $avis = new Avis();

        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
                $avis = $form->getData();
                $avis->setAuthor($this->getUser());
                $avis->setCreatedAt(new DateTime());
                $note = ($_POST['note']);
                $avis->setNote($note);
                

                $this->entityManager->persist($avis);
                $this->entityManager->flush();

                $this->addFlash('success', "Merci de nous avoir laissé votre avis !");
                return $this->redirectToRoute('account');
            }
                return $this->render('avis/avis.html.twig',[
                'avisForm' => $form->createView(),
        ]);

    } 
}
