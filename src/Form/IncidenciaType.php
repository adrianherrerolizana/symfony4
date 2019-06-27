<?php

namespace App\Form;

use App\Entity\Incidencia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncidenciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('descripcion')
            ->add('fechaCreacion', DateTimeType::class, [
            	'widget' => 'single_text'
			])
            ->add('resuelta', ChoiceType::class, [
            	'choices' => [
            		'No' => "0",
					'Sí' => "1"
				],
				'expanded' => true
			])
            ->add('fechaResolucion', DateType::class, [
            	'widget' => 'single_text'
			])
			->add('categoria')
			->add('tag')
			->add('urlImagen', FileType::class, [
					'label' => 'Adjunte una imagen',
					'data_class' => null,
					'required' => false
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Incidencia::class,
        ]);
    }
}
