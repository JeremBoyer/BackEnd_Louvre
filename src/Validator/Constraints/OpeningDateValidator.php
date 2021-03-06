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

        if (null === $value || '' === $value) {
            return;
        }
        if (
            // Tuesday
            $value->format('N') == 2 |
            $value->format('j:m') == $laborDay->format('j:m') |
            $value->format('j:m') == $toussaint->format('j:m') |
            $value->format('j:m') == $christmas->format('j:m') |
            mktime(
                $value->format('H'),
                $value->format('i'),
                $value->format('s'),
                $value->format('n'),
                $value->format('j'),
                $value->format('Y')) < mktime(
                $today->format('H'),
                $today->format('i'),
                $today->format('s'),
                $today->format('n'),
                $today->format('j'),
                $today->format('Y'))
        ) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value->format('j:m:Y'))
                ->addViolation();
        }
    }
}