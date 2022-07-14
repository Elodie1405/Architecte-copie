<?php

namespace App\Controller;

use App\Entity\Rdv;
use App\Entity\User;
use App\Form\EditUserType;
use App\Entity\Disponibility;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DisponibilityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/account", name="account")
     */
    public function account(): Response
    {
        return $this->render('account/account.html.twig');
    }

    /**
     * @Route("account/info/{id}", name="edit_info")
     */
    public function edit_info($id, Request $request): Response
    {

        $user = $this->entityManager->getRepository(User::class)->find($id);
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Vos données personnelles ont bien été modifiées !');
            return $this->redirectToRoute('account');
        }

        return $this->render('account/info.html.twig',[
            'editUserForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("account/mon_rdv/", name="mon_rdv")
     */
    public function monRDV(): Response
    {

        $rdv = $this->entityManager->getRepository(Rdv::class)->findBy(['customer'=>$this->getUser()]);

        //dd($rdv);
        return $this->render('account/mon_rdv.html.twig',[
            'rdvs' =>$rdv,
        ]);
    }

    /**
     * @Route("account/mon_rdv/annuler/{id}", name="annuler_rdv")
     */
    public function annulerRDV($id): Response
    {
        $dispo = $this->entityManager->getRepository(Disponibility::class)->find($id);
        
        //dd($dispo);
        $dispo->setReserved(false);
        $rdv = $dispo->getRdv();
        $this->entityManager->remove($rdv);
        $this->entityManager->flush();

        $this->addFlash('success', 'votre RDV a bien été annulé !');

        return $this->redirectToRoute('account');

        return $this->render('account/mon_rdv.html.twig',[

        ]);
    }


}
