<?php

namespace Qwer\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Qwer\UserBundle\Entity\User;
use Symfony\Component\BrowserKit\Request;

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
        //echo "lala";
        $request = $this->getRequest();
        $data = $request->get("user");
        print_r($data);
    }

}