<?php

namespace App\Form;


use Symfony\Component\Form\Extension\Core\Type\{
    NumberType,
    TextType,
    SubmitType
};
use Symfony\Component\Form\{
    AbstractType,
    FormBuilderInterface
};
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod(Request::METHOD_POST)
            ->add('name', TextType::class)
            ->add('amount', NumberType::class)
            ->add('update', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-warning'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
