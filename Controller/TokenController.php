<?php

namespace Qwer\UserBundle\Controller;

use Qwer\UserBundle\Entity\Token;
use Qwer\UserBundle\Event\TokenEvent;
use JMS\DiExtraBundle\Annotation as DI;

class TokenController extends RestController
{

    /**
     * @DI\Inject("qwer.auth")
     * @var \Qwer\UserBundle\Service\Authentication 
     */
    public $authentication;

    /**
     * @DI\Inject("event_dispatcher")
     * @var \Symfony\Component\EventDispatcher\EventDispatcher 
     */
    public $dispatcher;

    /**
     * @DI\Inject("doctrine.orm.entity_manager")
     * @var \Doctrine\ORM\EntityManager 
     */
    public $entityManager;

    public function postTokenAction($externalId)
    {
        $type = "Qwer\UserBundle\Entity\AuthenticationInfo";
        $info = $this->deserializeData($type);

        $user = $this->authentication->authenticate($info);

        $token = $this->findToken($user, $externalId);
        if(!$token){
            $token = new Token();
            $token->setUser($user);
            $token->setExternalId($externalId);
        }

        $event = new TokenEvent($user, $token);
        $this->dispatcher->dispatch("generate.token.event", $event);

        $view = $this->view($token);
        return $this->handleView($view);
    }

    private function findToken($user, $externalId)
    {
        $repo = $this->getTokenRepo();
        
        $criteria = array(
                "user" => $user, 
                "externalId" => $externalId
        );
        
        $token = $repo->findOneBy($criteria);
        
        return $token;
    }

    /**
     * 
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getTokenRepo()
    {
        $namespace = "QwerUserBundle:Token";
        $tokenRepo = $this->entityManager->getRepository($namespace);

        return $tokenRepo;
    }

}