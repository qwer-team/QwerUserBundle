<?php

namespace Qwer\UserBundle\Entity;

/**
 * Object sended from api clients.
 */
class RegistrationInfo extends AbstractAuthenticatedEntity
{
    /**
     *
     * @var string 
     */
    private $login;
    /**
     *
     * @var string 
     */
    private $email;
    /**
     *
     * @var string 
     */
    private $password;
    
    
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
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * 
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * 
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * 
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

}