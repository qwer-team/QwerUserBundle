<?php

namespace Qwer\UserBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use FOS\UserBundle\Model\UserManagerInterface;

class UserUniqueValidator extends ConstraintValidator
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
        $user = $this->userMananger->findUserByUsername($value);
        
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

    public function validate($value, Constraint $constraint)
    {
        $this->isValid($value, $constraint);
    }

}