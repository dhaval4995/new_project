<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class ProductType extends AbstractType
{
	public function buildform(FormBuilderInterface $builder, array $options){
		$builder
		        ->add('name', TextType::class, array('attr'=>array('class'=>'form-control col-md-6')))
		        ->add('price', TextType::class, array('attr'=>array('class'=>'form-control col-md-6')))
		        ->add('description', TextareaType::class, array('attr'=>array('class'=>'form-control col-md-6')))
		        ->add('save', SubmitType::class, array('attr'=>array('class'=>'btn btn-primary')))
		    ;    
	} 
}