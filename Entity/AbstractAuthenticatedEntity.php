<?php

namespace Qwer\UserBundle\Entity;


abstract class AbstractAuthenticatedEntity
{
    /**
     *
     * @var AuthenticationInfo
     */
    protected $authenticationInfo;
    
    /**
     * 
     * @return AuthenticationInfo
     */
    public function getAuthenticationInfo()
    {
        return $this->authenticationInfo;
    }

    /**
     * 
     * @param AuthenticationInfo $authentication
     */
    public function setAuthenticationInfo(AuthenticationInfo $authentication)
    {
        $this->authenticationInfo = $authentication;
    }
}