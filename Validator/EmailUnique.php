<?php

namespace Qwer\UserBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EmailUnique extends Constraint
{

    public $message = "Email is not unique. ";

    public function getMessage()
    {
        return $this->message;
    }

    public function validatedBy()
    {
        return "email.unique.validator";
    }

}