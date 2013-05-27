<?php

namespace Qwer\UserBundle\Entity;

/**
 * Description of Authentification
 *
 * @author root
 */
class AuthenticationInfo
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