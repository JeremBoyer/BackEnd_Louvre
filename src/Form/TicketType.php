<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $today = new \DateTime();
        $builder
            ->add('name')
            ->add('firstName')
            ->add('country', CountryType::class)
            ->add('type', ChoiceType::class, array(
                'choices' => array(
                    'Non' => 1,
                    'Oui' => 2
                )
            ))
            ->add(
                'priceType', CheckboxType::class, array(
                    'required' => false,
                )
            )
            ->add('visitAt', DateType::class, array(
                'format' => "dd-MM-yyyy",
                'placeholder' => array(
                    'day' => $today->format('j'), 'month' => $today->format("m"), 'year' => $today->format("Y"),
                )
            ))
            ->add('birthDate', BirthdayType::class, array(
                'format' => "dd-MM-yyyy"
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
