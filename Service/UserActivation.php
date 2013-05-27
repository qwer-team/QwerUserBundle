<?php

namespace Qwer\UserBundle\Service;

use Qwer\UserBundle\Entity\ActivationInfo;
use FOS\UserBundle\Util\UserManipulator;

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
        $this->authentication->authenticate($info->getAuthenticationInfo());
        $username = $info->getLogin();
        $active = $info->getActive();
        if ($active) {
            $this->manipulator->activate($username);
        } else {
            $this->manipulator->deactivate($username);
        }
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