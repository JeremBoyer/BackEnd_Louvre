<?php

namespace App\Validator\Constraints;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ThousandLimit extends Constraint
{
    public $message = 'La limite de visiteur est atteinte pour cette journée. Veuillez choisir un autre jour pour votre visite!';
}