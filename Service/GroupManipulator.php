<?php

namespace Qwer\UserBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Validator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Doctrine\ORM\EntityRepository;

use Qwer\UserBundle\Entity\GroupInfo;
use Qwer\UserBundle\Entity\Group;
use Qwer\UserBundle\Entity\AuthenticationInfo;
use Qwer\UserBundle\Event\GroupEvent;

class GroupManipulator extends ContainerAware
{

    /**
     *
     * @var \Doctrine\ORM\EntityRepository 
     */
    private $groupRepo;

    /**
     *
     * @var Authentication 
     */
    private $authentication;

    /**
     *
     * @var \Symfony\Component\Validator\Validator 
     */
    private $validator;

    /**
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * 
     * @param \Qwer\UserBundle\Entity\GroupInfo $info
     * @return \Qwer\UserBundle\Entity\Group
     * @throws \Exception
     */
    public function createGroup(GroupInfo $info)
    {
        $this->authentication->authenticate($info->getAuthenticationInfo());

        $name        = $info->getName();
        $description = $info->getDescription();
        
        $violations = $this->validator->validate($info);
        if (count($violations)) {
            $message = "";
            foreach ($violations as $violation) {
                $message .= $violation->getMessage();
            }
            throw new \Exception($message);
        }
        
        $group = new Group($name);
        $group->setDescription($description);
        
        $event = new GroupEvent($group);
        $this->dispatcher->dispatch("create.group.event", $event);

        return $group;
    }

    /**
     * 
     * @param \Qwer\UserBundle\Entity\GroupInfo $info
     * @param \Qwer\UserBundle\Entity\Group $group
     * @return \Qwer\UserBundle\Entity\Group
     * @throws \Exception
     */
    public function updateGroup(GroupInfo $info, Group $group)
    {
        $this->authentication->authenticate($info->getAuthenticationInfo());
        
        $violations = $this->validator->validate($info);
        if (count($violations)) {
            $message = "";
            foreach ($violations as $violation) {
                $message .= $violation->getMessage();
            }
            throw new \Exception($message);
        }
        
        $name        = $info->getName();
        $description = $info->getDescription();
        
        $group->setName($name);
        $group->setDescription($description);
        
        $event = new GroupEvent($group);
        $this->dispatcher->dispatch("update.group.event", $event);
        
        return $group;
    }

    /**
     * 
     * @param \Qwer\UserBundle\Entity\Group $group
     * @param \Qwer\UserBundle\Service\AuthenticationInfo $auth
     */
    public function removeGroup(Group $group, AuthenticationInfo $auth)
    {
        $this->authentication->authenticate($auth);
        
        $event = new GroupEvent($group);
        $this->dispatcher->dispatch("remove.group.event", $event);
    }

    /**
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $em = $container->get("doctrine.orm.entity_manager");
        $this->groupRepo = $em->getRepository("QwerUserBundle:Group");
    }

    /**
     * 
     * @param \Qwer\UserBundle\Service\Authentication $authentication
     */
    public function setAuthentication(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * 
     * @param \Qwer\UserBundle\Service\Validator $validator
     */
    public function setValidator(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * 
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

}