<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\SubCategory; 

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('id', TextType::class, [
                'required' => false,
            ])
            ->add('location', TextType::class )
            ->add('website', TextType::class)
            ->add('coordinates', TextType::class)
            ->add('tel', TextType::class)
            ->add('status', TextType::class, [
                'required' => false,
            ])
            ->add('discount', TextType::class)
            ->add('subCategory',EntityType::class, [
                'class' => SubCategory::class,
                'choice_label' => 'label'
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'data_class' => null
            ])
//            ->add('status', ChoiceType::class, [
//                'choices'  => array(
//                    'pending' => false,
//                    'valid' => true
//                )
//            ])

            ->add('save', SubmitType::class)
    ;}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}


