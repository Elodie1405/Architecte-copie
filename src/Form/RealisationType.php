<?php

namespace App\Form;

use App\Entity\Realisations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RealisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre :',
                'attr' => ['class' => 'form-style']
            ])
            ->add('subtitle', TextType::class, [
                'label' => 'Sous-titre :',
                'attr' => ['class' => 'form-style']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description :',
                'attr' => ['class' => 'form-style']
            ])
            ->add('picture', FileType::class, [
                'label' => 'Photo :',
                'attr' => ['class' => 'form-style']
            ])
            ->add('surface', TextType::class, [
                'label' => 'Surface :',
                'attr' => ['class' => 'form-style']
            ])
            ->add('style', TextType::class, [
                'label' => 'Style :',
                'attr' => ['class' => 'form-style']
            ])
            ->add('submit',SubmitType::class, [
                'label'=>'Ajouter une réalisation',
                'attr'=>['class'=>'realisation-btn']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Realisations::class,
            'allow_file_upload' => true,
        ]);
    }
}
