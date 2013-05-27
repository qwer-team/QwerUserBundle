<?php

namespace Qwer\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Qwer\UserBundle\Entity\User;
use Qwer\UserBundle\Entity\Token;

class TokenEvent extends Event
{
    /**
     *
     * @var \Qwer\UserBundle\Entity\User 
     */
    private $user;
    
    /**
     *
     * @var \Qwer\UserBundle\Entity\Token 
     */
    private $token;
    
    /**
     * 
     * @param \Qwer\UserBundle\Entity\User $user
     * @param \Qwer\UserBundle\Entity\Token $token
     */
    function __construct(User $user, Token $token)
    {
        $this->user = $user;
        $this->token = $token;
    }
    
    /**
     * 
     * @return \Qwer\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * 
     * @return \Qwer\UserBundle\Entity\Token
     */
    public function getToken()
    {
        return $this->token;
    }

}