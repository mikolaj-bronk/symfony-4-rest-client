<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{
    NumberType,
    SubmitType,
    TextType
};
use Symfony\Component\{
    Form\FormBuilderInterface,
    HttpFoundation\Request,
    OptionsResolver\OptionsResolver
};

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod(Request::METHOD_POST)
            ->add('name', TextType::class)
            ->add('amount', NumberType::class)
            ->add('create', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
