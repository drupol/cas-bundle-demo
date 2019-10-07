<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PgtValidate.
 */
class PgtValidate extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $link = sprintf('%s?ticket=%s', $options['data']['service'], $options['data']['ticket']);
        $commandLine = sprintf('curl -b cookie.txt -c cookie.txt -v -L -k "%s"', $link);

        $builder
            ->add('service', TextType::class, ['label' => 'Proxy service (service)', 'attr' => ['readonly' => true]])
            ->add('ticket', TextType::class, ['label' => 'Proxy ticket (ticket)', 'attr' => ['readonly' => true]])
            ->add(
                'link',
                TextType::class,
                [
                    'label' => 'Link (open it in a different session window)',
                    'data' => $link,
                    'attr' => [
                        'readonly' => true,
                    ],
                ]
            )
            ->add(
                'commandLine',
                TextType::class,
                [
                    'label' => 'Curl command line (as Anonymous)',
                    'data' => $commandLine,
                    'attr' => [
                        'readonly' => true,
                    ],
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Validate a proxy ticket',
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
