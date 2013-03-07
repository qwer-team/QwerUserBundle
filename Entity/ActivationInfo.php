<?php

namespace Qwer\UserBundle\Entity;

use Qwer\UserBundle\Entity\Authentication;

class ActivationInfo extends AbstractAuthenticatedEntity
{
    /**
     *
     * @var string 
     */
    private $login;
    
    /**
     *
     * @var boolean 
     */
    private $active;
    
    /**
     * 
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * 
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * 
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * 
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

}