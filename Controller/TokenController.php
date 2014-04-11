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

    public function postTokenCurrencyAction($externalId, $currency)
    {

        $type = "Qwer\UserBundle\Entity\AuthenticationInfo";
        //  print_r($type);
        $info = $this->deserializeData($type);
    //    print_r($info);
        //$user = $this->getDoctrine()->getRepository('QwerLottoBundle:User')->find(1);//
        $user = $this->authentication->authenticate($info);

        $token = $this->findToken($user, $externalId);
        if (!$token) {
            $currencyRepo = $this->getCurrencyRepo();
            $currency = $currencyRepo->findOneByCode($currency);
            $class = $this->get('service_container')->getParameter('users.token_class');
            $token = new $class();
            $token->setUser($user);
            $token->setExternalId($externalId);
            $token->setCurrency($currency);
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
        $class = $this->get('service_container')->getParameter('users.token_class');
        $tokenRepo = $this->entityManager->getRepository($class);

        return $tokenRepo;
    }

    private function getCurrencyRepo()
    {
        $namespace = "QwerLottoDocumentsBundle:Currency";
        $curRepo = $this->entityManager->getRepository($namespace);

        return $curRepo;
    }

}