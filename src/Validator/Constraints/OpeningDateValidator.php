<?php
namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OpeningDateValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        // TODO: Implement validate() method.
        //$publicHoliday = new \DateTime();
        $today = new \DateTime();
        $toussaint = new \DateTime();
        $laborDay = new \DateTime();
        $christmas = new \DateTime();

        $laborDay = $laborDay->setDate(null, 5, 1);
        $toussaint = $toussaint->setDate(null, 11,1);
        $christmas = $christmas->setDate(null, 12, 25);
        dump($value, $today, $christmas, $toussaint, $laborDay);
        if (null === $value || '' === $value) {
            return;
        }

        if (// Tuesday
            $value->format('N') == 2 |
            // Saturday
            $value->format('N') == 6 |
            // Sunday
            $value->format('N') == 7 |
            $value->format('j:m') == $laborDay->format('j:m') |
            $value->format('j:m') == $toussaint->format('j:m') |
            $value->format('j:m') == $christmas->format('j:m') |
            $value->format('j:m:Y') < $today->format('j:m:Y')) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value->format('j:m:Y'))
                ->addViolation();
        }

    }
}