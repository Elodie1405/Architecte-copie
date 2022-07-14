<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label'=>'Nom :',
                'attr'=>['placeholder'=>'Nom',
                'class' => 'form-style']
            ])
            ->add('firstname', TextType::class, [
                'label'=>'Prénom :',
                'attr'=>['placeholder'=>'Prénom',
                'class' => 'form-style']
            ])
            ->add('raison_sociale', TextType::class, [
                'required'=>false,
                'label'=>'Raison sociale :',
                'attr'=>['placeholder'=>'Entreprise', 
                'class' => 'form-style']
            ])
            ->add('city', TextType::class, [
                'label'=>'Ville :',
                'attr'=>['placeholder'=>'Ville',
                'class' => 'form-style']
            ])
    
            ->add('email',EmailType::class, [
                'required'=>true,
                'label'=>'Email :',
                'attr'=>['placeholder'=>'Email','class' => 'form-style']
            ])
    
            ->add('submit',SubmitType::class, [
                    'label'=>'Modifier mes informations',
                    'attr'=>['class'=>'register-btn']
                ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
