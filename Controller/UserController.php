<?php

namespace Qwer\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use JMS\Serializer\Serializer;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Description of UserController
 * 
 * @author root
 */
class UserController extends RestController
{

    /**
     * @DI\Inject("fos_user.user_manager")
     * @var \FOS\UserBundle\Model\UserManagerInterface 
     */
    public $userManager;

    /**
     * @DI\Inject("user.registration")
     * @var \Qwer\UserBundle\Service\UserRegistration 
     */
    public $registration;

    /**
     * @DI\Inject("user.activation")
     * @var \Qwer\UserBundle\Service\UserActivation  
     */
    public $activation;

    /**
     * @DI\Inject("role_manager")
     * @var \Qwer\UserBundle\Service\RoleManager
     */
    public $roleManager;

    /**
     * @DI\Inject("fos_user.user_manager")
     * @var string 
     */
    private $userNotFoundMessage = "User %s was not found.";

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

    public function postUsersNewAction()
    {
        $type = "Qwer\UserBundle\Entity\RegistrationInfo";
        $registrationInfo = $this->deserializeData("data", $type);
        $user = $this->registration->createUser($registrationInfo);

        $view = $this->view($user);
        return $this->handleView($view);
    }

    public function postActivateUserAction()
    {
        $type = "Qwer\UserBundle\Entity\ActivationInfo";
        $activationInfo = $this->deserializeData("data", $type);
        $this->activation->activate($activationInfo);

        $username = $activationInfo->getLogin();
        $user = $this->userManager->findUserByUsername($username);
        $view = $this->view($user);
        return $this->handleView($view);
    }

    public function postUserAddRoleAction($login)
    {
        $user = $this->userManager->findUserByUsername($login);
        if (!$user) {
            $message = sprintf($this->userNotFoundMessage, $login);
            throw new ResourceNotFoundException($message);
        }

        $type = "Qwer\UserBundle\Entity\RoleInfo";
        $roleInfo = $this->deserializeData("data", $type);

        $this->roleManager->addRolesToUser($user, $roleInfo);

        $view = $this->view($user);
        return $this->handleView($view);
    }

    public function postUserRemoveRoleAction($login)
    {
        $user = $this->userManager->findUserByUsername($login);
        if (!$user) {
            $message = sprintf($this->userNotFoundMessage, $login);
            throw new ResourceNotFoundException($message);
        }

        $type = "Qwer\UserBundle\Entity\RoleInfo";
        $roleInfo = $this->deserializeData("data", $type);

        $this->roleManager->removeRolesFromUser($user, $roleInfo);

        $view = $this->view($user);
        return $this->handleView($view);
    }

}