<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PgtRequest.
 */
class PgtRequest extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('targetService', TextType::class, ['label' => 'Target service (targetService)'])
            ->add('pgt', TextType::class, ['label' => 'Proxy granting ticket (pgt)', 'attr' => ['readonly' => true]])
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Request a new proxy ticket (PT)',
                    'attr' => [
                        'class' => 'btn btn-primary btn-lg btn-block',
                    ],
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
