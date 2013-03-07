<?php

namespace Qwer\UserBundle\Service;

use Qwer\UserBundle\Entity\ActivationInfo;

class UserActivation
{

    /**
     *
     * @var \FOS\UserBundle\Util\UserManipulator 
     */
    private $manipulator;

    /**
     *
     * @var Authentication 
     */
    private $authentication;

    public function activate(ActivationInfo $info)
    {
        $this->authentication;
    }
    
    public function setUserManipulator(UserManipulator $userManipulator)
    {
        $this->manipulator = $userManipulator;
    }
    
    public function setAuthentication($authentication)
    {
        $this->authentication = $authentication;
    }

}