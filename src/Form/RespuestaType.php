<?php

namespace App\Form;

use App\Entity\Pregunta;
use App\Entity\Respuesta;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RespuestaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marca_temporal', null, [
                'widget' => 'single_text',
            ])
            ->add('respuesta_elegida')
            ->add('Pregunta', EntityType::class, [
                'class' => Pregunta::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Respuesta::class,
        ]);
    }
}
