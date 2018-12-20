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
        $visitors = count($ticketsAtValue) + count($sessionTickets);
        if ($visitors >= 10) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}