<?php
namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class OpeningDate extends Constraint
{
    public $message = 'Le "{{ string }}" ne correspond pas à une date d\'ouverture du musée';
}