<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\SubCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('location', TextType::class )
            ->add('website', TextType::class, [
                'required' => false
            ])
            ->add('coordinates', TextType::class)
            ->add('tel', TelType::class)
            ->add('discount', TextType::class)
            ->add('status', ChoiceType::class,[
                'choices' => [
                    'Valid' => 'valid',
                    'Pending' => 'pending',
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Fichier image',
                'attr' => ['placeholder' => 'Choose file'],
                'required' => false,
                'data_class' => null
            ])
            ->add('subCategory', EntityType::class, [
                'class' => SubCategory::class,
                'choice_label' => 'label'
            ])
            ->add('enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}

