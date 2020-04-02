<?php

namespace App\Form;

use App\Entity\ORM\Verification;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VerificationUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('users', EntityType::class, [
                'required' => false,
                'class' => User::class,
                'label' => false,
                'multiple' => true,
                'choice_label' => function (User $user) {
                    return $user->getId().' '.$user->getName().' '.$user->getSurname();
                },
            ])
            ->add('choice', ChoiceType::class, [
                'required' => false,
                'expanded' => true,
                'label' => 'verification:',
                'multiple' => false,
                'choices' => [
                    'true' => true,
                    'false' => false,
                ],
                'data' => true,
                'empty_data' => false,
                'placeholder' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Verification::class,
        ]);
    }
}
