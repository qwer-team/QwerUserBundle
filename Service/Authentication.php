<?php

namespace Qwer\UserBundle\Service;

use Qwer\UserBundle\Entity\AuthenticationInfo;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Authentication
{

    /**
     *
     * @var \FOS\UserBundle\Model\UserManagerInterface 
     */
    private $userManager;

    /**
     *
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface  
     */
    private $encoderFactory;

    public function authenticate(AuthenticationInfo $info)
    {
        $login = $info->getLogin();
        $plainPassword = $info->getPassword();

        $user = $this->userManager->findUserByUsername($login);
        if (is_null($user)) {
            $message = sprintf("Bad auth data. ");
            throw new ResourceNotFoundException($message);
        }

        $salt = $user->getSalt();
        $encoder = $this->encoderFactory->getEncoder($user);

        $password = $encoder->encodePassword($plainPassword, $salt);
        if ($password != $user->getPassword()) {
            $message = sprintf("Bad auth data. ");
            throw new ResourceNotFoundException($message);
        }
        
        return $user;
    }

    public function setUserManager(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public function setEncoderFactory($encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

}