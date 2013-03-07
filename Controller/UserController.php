<?php

namespace Qwer\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Qwer\UserBundle\Entity\User;
use Symfony\Component\BrowserKit\Request;
use JMS\Serializer\Serializer;

/**
 * Description of UserController
 *
 * @author root
 */
class UserController extends FOSRestController
{

    /**
     *
     * @var \FOS\UserBundle\Model\UserManagerInterface 
     */
    public $userManager;

    /**
     *
     * @var \JMS\Serializer\Serializer 
     */
    public $serializer;

    /**
     *
     * @var \Qwer\UserBundle\Service\UserRegistration 
     */
    public $registration;
    
    /**
     *
     * @var \Qwer\UserBundle\Service\UserActivation  
     */
    public $activation;

    public function getUserInfoAction($login)
    {
        $user = $this->userManager->findUserByUsername($login);
        $view = $this->view($user);
        if (!$user) {
            $message = sprintf("User %s was not found", $login);
            throw new ResourceNotFoundException($message);
        }

        return $this->handleView($view);
    }

    public function postNewUserAction()
    {
        $type = "Qwer\UserBundle\Entity\RegistrationInfo";
        $registrationInfo = $this->deserializeData("data", $type);
        $user = $this->registration->createUser($registrationInfo);
        
        $view = $this->view($user);
        return $this->handleView($view);
    }

    public function activateUserAction()
    {
        $type = "Qwer\UserBundle\Entity\ActivationInfo";
        $activationInfo = $this->deserializeData("data", $type);
        $user = $this->activation->activate($activationInfo);
        
        $view = $this->view($user);
        return $this->handleView($view);
    }

    private function deserializeData($paramName, $type)
    {
        $request = $this->getRequest();
        $data = $request->get($paramName);
        $format = $this->getRequest()->get("_format");
        
        $deserialized = $this->serializer->deserialize($data, $type, $format);
        
        return $deserialized;
    }

}