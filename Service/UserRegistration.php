<?php

namespace Qwer\UserBundle\Service;

use Qwer\UserBundle\Entity\RegistrationInfo;
use FOS\UserBundle\Util\UserManipulator;
use Symfony\Component\Validator\Validator;
use Qwer\UserBundle\Service\Authentication;

class UserRegistration
{

    /**
     *
     * @var \FOS\UserBundle\Util\UserManipulator 
     */
    private $manipulator;

    /**
     *
     * @var \Symfony\Component\Validator\Validator 
     */
    private $validator;
    
    /**
     *
     * @var Authentication 
     */
    private $authentication;

    public function createUser(RegistrationInfo $info)
    {
        $this->authentication->authenticate($info->getAuthenticationInfo());
        $violations = $this->validator->validate($info);
        if (count($violations)) {
            $message = "";
            foreach ($violations as $violation) {
                $message .= $violation->getMessage();
            }
            throw new \Exception($message);
        }
        $login = $info->getLogin();
        $email = $info->getEmail();
        $password = $info->getPassword();

        $user = $this->manipulator->create($login, $password, $email, false, false);

        return $user;
    }

    public function setUserManipulator(UserManipulator $userManipulator)
    {
        $this->manipulator = $userManipulator;
    }

    public function setValidator(Validator $validator)
    {
        $this->validator = $validator;
    }
    
    public function setAuthentication($authentication)
    {
        $this->authentication = $authentication;
    }

}