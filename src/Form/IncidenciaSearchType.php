<?php

namespace App\Form;

use App\Entity\Categoria;
use App\Entity\Incidencia;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncidenciaSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tituloSearch', TextType::class, [
            	'required' => false,
           		'label' => 'Título',
			])
           ->add('categoriaSearch', EntityType::class, [
           		'label' => 'Categoría',
			   	'class' => Categoria::class,
			   	'required'   => false,
		   ])
			->add('submit', SubmitType::class, [
				'label' => 'Filtrar'
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
