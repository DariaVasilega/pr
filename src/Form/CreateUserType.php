<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
            ])
            ->add('surname', TextType::class, [
                'required' => false,
            ])
            ->add('status', TextType::class, [
                'required' => false,
            ])
            ->add('avatar', FileType::class, [
                'required' => false,
                'attr' => [
                    'hidden' => true,
                ],
            ])
            ->add('password', PasswordType::class, [
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'required' => false,
            ])
            ->add('login', TextType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'hidden' => true,
                ],
            ])
        ;
    }
}
