<?php

namespace App\Validator\Constraints;

use App\Entity\Ticket;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ThousandLimitValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    public function validate($value, Constraint $constraint)
    {
        // TODO: Implement validate() method.
        $ticketsAtValue = $this->entityManager
            ->getRepository(Ticket::class)
            ->findBy(
                ['visitAt' => $value]
            );
        $sessionTickets = $this->session->get("tickets");
        $visitors = 0;
        if (!empty($ticketsAtValue)) {
            $visitors = count($ticketsAtValue);
        } elseif (!empty($sessionTickets)) {
            $visitors = $visitors + count($sessionTickets);
        }
        if (isset($visitors)) {
            if ($visitors >= 1000) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }

    }
}