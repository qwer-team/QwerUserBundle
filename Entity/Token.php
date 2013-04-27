<?php

namespace Qwer\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Token
 */
class Token
{

    /**
     * @var string
     */
    private $token;

    /**
     * @var integer
     */
    private $externalId;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Qwer\UserBundle\Entity\User
     */
    private $user;
    
    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Entity\Currency 
     */
    private $currency;

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
    private $expiresAt;

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