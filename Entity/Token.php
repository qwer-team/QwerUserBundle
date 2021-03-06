<?php

namespace Qwer\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Token
 */
abstract class Token
{

    /**
     * @var string
     */
    protected $token;

    /**
     * @var integer
     */
    protected $externalId;
    
    /**
     * @var string
     */
    protected $externalLogin;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Qwer\UserBundle\Entity\User
     */
    protected $user;
    
    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Entity\Currency 
     */
    protected $currency;

    public function __construct()
    {
        //$this->updateExpireDate();
    }

    public function updateExpireDate()
    {
        $date = new \DateTime();
        $interval = \DateInterval::createFromDateString("5 minutes");
        $date->add($interval);

        $this->setExpiresAt($date);
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Token
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set externalId
     *
     * @param integer $externalId
     * @return Token
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Get externalId
     *
     * @return integer 
     */
    public function getExternalId()
    {
        return $this->externalId;
    }
    
    /**
     * Set token
     *
     * @param string $externalLogin
     * @return Token
     */
    public function setExternalLogin($externalLogin)
    {
        $this->externalLogin = $externalLogin;

        return $this;
    }

    /**
     * Get externalLogin
     *
     * @return string 
     */
    public function getExternalLogin()
    {
        return $this->externalLogin;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \Qwer\UserBundle\Entity\User $user
     * @return Token
     */
    public function setUser(\Qwer\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Qwer\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @var \DateTime
     */
    protected $expiresAt;

    /**
     * Set expiresAt
     *
     * @param \DateTime $expiresAt
     * @return Token
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt
     *
     * @return \DateTime 
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * 
     * @return \Qwer\LottoBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->getUser()->getClient();
    }

    /**
     * 
     * @return \Qwer\LottoDocumentsBundle\Entity\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

}