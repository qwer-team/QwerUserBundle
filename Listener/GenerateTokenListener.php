<?php

namespace Qwer\UserBundle\Listener;

use Doctrine\ORM\EntityManager;
use Qwer\UserBundle\Service\TokenHashGenerator;
use Qwer\UserBundle\Event\TokenEvent;

class GenerateTokenListener
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager 
     */
    private $em;

    /**
     * @var \Qwer\UserBundle\Service\TokenHashGenerator 
     */
    public $tokenHashGenerator;

    public function onEvent(TokenEvent $event)
    {
        $user = $event->getUser();
        $token = $event->getToken();
        
        if (new \DateTime() > $token->getExpiresAt() || is_null($token->getExpiresAt())) {
            $hash = $this->tokenHashGenerator->generate($user, $token);
            $token->setToken($hash);
        }
        $token->updateExpireDate();
        $this->em->persist($token);
        $this->em->flush();
    }

    /**
     * 
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setTokenHashGenerator(TokenHashGenerator $tokenHashGenerator)
    {
        $this->tokenHashGenerator = $tokenHashGenerator;
    }

}