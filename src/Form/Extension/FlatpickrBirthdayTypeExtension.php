<?php
namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FlatpickrBirthdayTypeExtension
 * @package App\Form\Extension
 */
class FlatpickrBirthdayTypeExtension extends AbstractTypeExtension
{
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        // use FormType::class to modify (nearly) every field in the system
        return BirthdayType::class;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'widget' => 'single_text',
            'attr' => array(
                'placeholder' => 'Sélectionner une date',
                'class' => 'flatpickr flatpickr-input'
            ),
        ));
    }
}