<?php

namespace Qwer\UserBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use FOS\UserBundle\Model\UserManagerInterface;

class EmailUniqueValidator extends ConstraintValidator
{

    /**
     *
     * @var \FOS\UserBundle\Model\UserManagerInterface 
     */
    private $userMananger;

    /**
     * 
     * @param string $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     */
    protected function isValid($value, Constraint $constraint)
    {
        $user = $this->userMananger->findUserByEmail($value);
        
        if ($user) {
            $message = $constraint->getMessage();
            $this->context->addViolation($message);
        }
    }

    /**
     * 
     * @param \FOS\UserBundle\Model\UserManagerInterface $userMananger
     */
    public function setUserMananger(UserManagerInterface $userMananger)
    {
        $this->userMananger = $userMananger;
    }

}