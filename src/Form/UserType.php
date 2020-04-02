<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,
                [
                    'label' => 'Name: ',
                    'attr' => ['class' => 'inputs'],
                ])
            ->add('surname',TextType::class,
                [
                    'label' => 'Surname: ',
                    'attr' => ['class' => 'inputs'],
                ])
//            ->add('avatar')
//            ->add('status')
//            ->add('groups')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
