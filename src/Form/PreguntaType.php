<?php

namespace App\Form;

use App\Entity\Pregunta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreguntaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('enunciado')
            ->add('opcion_a')
            ->add('opcion_b')
            ->add('opcion_c')
            ->add('opcion_d')
            ->add('opcion_correcta')
            ->add('f_inicio', null, [
                'widget' => 'single_text',
            ])
            ->add('f_fin', null, [
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pregunta::class,
        ]);
    }
}
