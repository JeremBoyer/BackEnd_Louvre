<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
            ->add(
                'priceType', CheckboxType::class, array(
                    'required' => false,
                )
            )
            ->add('visitAt', DateTimeType::class, array(
                'label' => "Date de la visite",
            ))
            ->add('birthDate', BirthdayType::class, array(
                'label' => "Date d'anniversaire",
                "widget" => "single_text"
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
