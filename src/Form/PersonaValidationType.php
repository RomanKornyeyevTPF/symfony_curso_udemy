<?php

namespace App\Form;

use App\Entity\PersonaEntityValidation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonaValidationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, ['label' => 'nombre'])
            ->add('correo', TextType::class, ['label' => 'email'])
            ->add('telefono', TextType::class, ['label' => 'telefono'])
            ->add('pais', ChoiceType::class, [
                'choices'=>[
                    'Seleccione...' => 0,
                    'Chile' => 1,
                    'Perú' => 2,
                    'México' => 3,
                    'España' => 4,
                    'Bolivia' => 5,
                    'Venezuela' => 6,
                    'Costa Rica' => 7,
                    'Noruega' => 8
                ]
            ])
            ->add('intereses', ChoiceType::class, [
                'choices' => [
                    'Deporte' => 'deporte',
                    'Música' => 'musica',
                    'Tecnología' => 'tecnologia',
                    'Ciencia' => 'ciencia',
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Intereses'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'=>PersonaEntityValidation::class,
            'csrf_protection'=> true,
            'csrf_field_name'=> 'token'
        ]);
    }
}