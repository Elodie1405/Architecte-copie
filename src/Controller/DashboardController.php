<?php

namespace App\Controller;

use App\Entity\Rdv;
use App\Entity\User;
use App\Form\RdvType;
use App\Entity\Calendar;
use App\Form\AgendaType;
use App\Entity\Realisations;
use App\Entity\Disponibility;
use App\Form\EditRealisationType;
use App\Repository\CalendarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("admin/dashboard", name="dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('dashboard/dashboard.html.twig');
    }

    /**
     * @Route("admin/dashboard/realisations", name="list-real")
     */
    public function listReal(): Response
    {
        $realisations = $this->entityManager->getRepository(Realisations::class)->findAll();
    
        return $this->render('dashboard/list-real.html.twig',[
            'realisations' => $realisations,
        ]);
    }

    /**
     * @Route("/admin/edit/realisation/{id}", name="edit_realisation")
     */
    public function editRealisation($id, Request $request, SluggerInterface $slugger): Response
    {
        
        $realisation = $this->entityManager->getRepository(Realisations::class)->find($id);

        $oldPicture = $realisation->getPicture();

        $form = $this->createForm(EditRealisationType::class,$realisation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){


            if ($realisation->getPicture() !== null && $realisation->getPicture() !== $oldPicture) {
                $file = $form->get('picture')->getData();
                $extension = '.' . $file->guessExtension();
                $safeFilename = $slugger->slug($realisation->gettitle());
                $newFilename = $safeFilename . '_' . uniqid() . $extension;
                try {
                    $file->move($this->getParameter('projets_dir'), $newFilename);
                    $realisation->setPicture($newFilename);
                } catch (FileException $exception) {
                }
                $realisation->setPicture($newFilename);
            }else{
                $realisation->setPicture($oldPicture);
            }

            $this->entityManager->persist($realisation);
            $this->entityManager->flush();
            $this->addFlash('success', 'Réalisation modifiée !');
            return $this->redirectToRoute('list-real');
        }

        return $this->render('dashboard/editRealisation.html.twig',[
            'realisation' => $realisation,
            'editRealisationForm' => $form->createView(),
        ]);

    }


    /**
     * @Route("/admin/delete/realisation/{id}", name="delete_realisation")
     */
    public function deleteRealisation(Realisations $realisation): Response
    {
        $this->entityManager->remove($realisation);
        $this->entityManager->flush();
        $this->addFlash('success', 'Réalisation supprimée !');
        return $this->redirectToRoute('list-real');
    }


    /**
     * @Route("admin/dashboard/rdvs", name="list-rdvs")
     */
    public function listRdvs(): Response
    {
        $rdvs = $this->entityManager->getRepository(Rdv::class)->findAll(['customer'=>$this->getUser()]);
        
        return $this->render('dashboard/list-rdvs.html.twig',[
            'rdvs' => $rdvs,
        ]);
    }

    /**
     * @Route("/admin/delete/rdv/{id}", name="delete_rdv")
     */
    public function deleteRdv($id): Response
    {
        $dispo = $this->entityManager->getRepository(Disponibility::class)->find($id);
        
        //dd($dispo);
        $dispo->setReserved(false);
        $rdv = $dispo->getRdv();
        $this->entityManager->remove($rdv);
        $this->entityManager->flush();

        $this->addFlash('success', 'le RDV a bien été annulé !');

        return $this->redirectToRoute('list-rdvs');
    }

    /**
     * @Route("admin/calendar", name="calendar")
     */
    public function calendar(CalendarRepository $calendar, Request $request): Response
    {

        $events = $calendar->findAll();
        //dd($events);

        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'backgroundColor' => $event->getBackgroundColor(),
                'allDay' => $event->isAllday(),
            ];
        }

        $data = json_encode($rdvs);
        

        return $this->render('dashboard/calendar.html.twig', compact('data'));
    }

    /**
     * @Route("/admin/calendar/nouveau_rdv", name="nouveau_rdv", methods={"GET|POST"})
     */
    public function addRdv(Request $request): Response
    {
        $rdv = new Calendar();
        $form = $this->createForm(AgendaType::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
                $rdv = $form->getData();
                $rdv->setAllDay(false);


                $this->entityManager->persist($rdv);
                $this->entityManager->flush();
                $this->addFlash('success', 'Votre RDV a bien été pris en compte !');
                $this->redirectToRoute('calendar');
            }
                return $this->render('dashboard/agenda.html.twig',[
                'rdvForm' => $form->createView(),
        ]);

     
    } 


}
