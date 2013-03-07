<?php

namespace Qwer\UserBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UserUnique extends Constraint
{

    public $message = "Login is not unique. ";

    public function getMessage()
    {
        return $this->message;
    }

    public function validatedBy()
    {
        return "user.unique.validator";
    }

}