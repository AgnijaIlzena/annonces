<?php

namespace App\Form;

use App\Entity\Advert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre d\'annonce',
                'required'=>false
            ])
            ->add('text', TextareaType::class, [
                'label'=> 'Description d\'annone',
                'required'=>false
            ])
            ->add('cover', TextType::class, [
                'label' => 'Image d\'annonce',
                'required'=>false
            ])
            ->add('phone', TelType::class, [
                'label'=> 'Votre numero de telephone',
                'required'=>false
            ])
            ->add('postCode', NumberType::class, [
                'label'=>'Code postal',
                'required'=>false
            ])
            ->add('created_at', DateType::class, [
                'label'=>'Date de creation d\'annonce',
                'required'=>false,
                'input' => 'datetime_immutable',
                'widget' => 'single_text' 
            ])
            ->add('save', SubmitType::class, [
                'label'=> 'Enregister'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}
