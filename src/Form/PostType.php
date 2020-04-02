<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('id', IntegerType::class,
//                [
//                    'label' => false,
//                    'attr' => [
//                        'hidden' => true,
//                    ],
//                ])
            ->add('text',TextareaType::class,
                [
                    'label' => false,
                    'attr' => [
                        'class' => 'm-1',
                    ],
                ])
            //->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
