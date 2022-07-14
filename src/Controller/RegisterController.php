<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher){
        $this->entityManager = $entityManager; 
        $this->passwordHasher = $passwordHasher;
    } 


    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request): Response
    {

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $data = $request->request->get('register_form', []);
            //dd($data);
            if(array_key_exists('roles', $data )) {
            $user->setRoles([$data['roles']]);
            }
            //dd($user);
            $user = $form->getData();

            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword())
        );

        $this->entityManager->persist($user); 
        $this->entityManager->flush(); 
        $this->addFlash('success', 'Votre compte a bien été créé, merci à vous !');
        return $this->redirectToRoute('app_login');

        //dd($form); 

        }

        return $this->render('register/register.html.twig', [
            'registerForm' => $form->createView(),
        ]);
    }
}
