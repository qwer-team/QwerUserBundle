<?php

namespace Qwer\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use JMS\Serializer\Serializer;
use JMS\DiExtraBundle\Annotation as DI;
use Qwer\UserBundle\Entity\Users;

/**
 * Description of UserController
 * 
 * @author root
 */
class UserController extends RestController
{

    /**
     * @DI\Inject("qwer.user_manager")
     * @var \Qwer\UserBundle\Service\UserManager
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
     * @DI\Inject("doctrine.orm.entity_manager")
     * @var \Doctrine\ORM\EntityManager 
     */
    public $em;

    /**
     * @DI\Inject("qwer.user_manager")
     * @var string 
     */
    private $userNotFoundMessage = "User %s was not found.";

    /**
     * @DI\Inject("qwer.auth")
     * @var \Qwer\UserBundle\Service\Authentication 
     */
    private $auth;

    /**
     *
     * @var string 
     */
    private $rolesInfoNamespace = "Qwer\UserBundle\Entity\RolesInfo";

    /**
     * Users list.
     * Список пользователей.<br />
     * Method url: /users.{_format}.<br />
     * Response examle:
     * <pre>
     *  {
     *
     *    "status": "success",
     *    "data": {
     *        "users": [
     *            {
     *                "id": 1,
     *                "username": "rasom",
     *                "email": "rasom@ukr.net",
     *                "enabled": true,
     *                "last_login": "2013-03-06T15:48:46+0200",
     *                "groups": [
     *                    {
     *                        "id": 1,
     *                        "name": "admin",
     *                        "roles": [
     *                            "ROLE_ADMIN"
     *                        ],
     *                        "description": ""
     *                    }
     *                ],
     *                "locked": false,
     *                "expired": false,
     *                "roles": [ ],
     *                "credentials_expired": false
     *            },
     *            {
     *                "id": 2,
     *                "username": "vassa",
     *                "email": "vassa@ukr.net",
     *                "enabled": false,
     *                "groups": [ ],
     *                "locked": false,
     *                "expired": false,
     *                "roles": [
     *                    "ROLE_CASHIER"
     *                ],
     *                "credentials_expired": false
     *            }, ....
     * </pre>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUsersAction()
    {
        $users = new Users();
        $users->setUsers($this->userManager->findUsers());
        $view = $this->view($users);
        return $this->handleView($view);
    }

    /**
     * Get user info action.  
     * Инфа по конкретному пользователю.<br />
     * Method url: /users/{login}.{_format}.<br />
     * <pre>{
     *
     *    "status": "success",
     *    "data": {
     *        "id": 1,
     *        "username": "rasom",
     *        "email": "rasom@ukr.net",
     *        "enabled": true,
     *        "last_login": "2013-03-06T15:48:46+0200", 
     *        "groups": [
     *            {
     *                "id": 1,
     *                "name": "admin",
     *                "roles": [
     *                    "ROLE_ADMIN"
     *                ],
     *                "description": ""
     *            }
     *        ],
     *        "locked": false,
     *        "expired": false,
     *        "roles": [ ], 
     *        "credentials_expired": false  
     *    }
     *
     * }</pre>
     * @param string $login
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUserAction($login)
    {
        $user = $this->findUser($login);
        
        $view = $this->view($user);
        return $this->handleView($view);
    }

    /**
     * User registration.
     * Регистрация нового пользователя. <br />
     * Пример запроса:
     * <pre>
     * &lt;?xml version="1.0" encoding="UTF-8"?&gt;
     * &lt;request&gt;
     *    &lt;login&gt;<b>newUser123</b>&lt;/login&gt;
     *    &lt;email&gt;<b>vassa12345</b>@ukr.net&lt;/email&gt;
     *    &lt;password&gt;<b>12356</b>&lt;/password&gt;
     *    &lt;authentication&gt;
     *        &lt;login&gt;<b>rasom</b>&lt;/login&gt;
     *        &lt;password&gt;<b>123</b>&lt;/password&gt;
     *    &lt;/authentication&gt;
     * &lt;/request&gt;
     * </pre>
     * Ответ такой же как иприпросмотре инфы по юзеру.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postUsersNewAction()
    {
        $type = "Qwer\UserBundle\Entity\RegistrationInfo";
        $registrationInfo = $this->deserializeData($type);
        $user = $this->registration->createUser($registrationInfo);

        $view = $this->view($user);
        return $this->handleView($view);
    }

    /**
     * User activation and deactivation.
     * Активация и деактивация пользователя. <br />
     * Запрос: <br />
     * <pre>
     * &lt;?xml version="1.0" encoding="UTF-8"?&gt;
     * &lt;request&gt;
     *    &lt;login&gt;<b>newUser123</b>&lt;/login&gt; 
     *    &lt;active&gt;<b>true</b>&lt;/active&gt;
     *    &lt;authentication&gt;
     *        &lt;login&gt;<b>rasom</b>&lt;/login&gt;
     *        &lt;password&gt;<b>123</b>&lt;/password&gt;
     *    &lt;/authentication&gt;
     * &lt;/request&gt;
     * </pre>
     * Ответ такой же как иприпросмотре инфы по юзеру.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postActivateUserAction()
    {
        $type = "Qwer\UserBundle\Entity\ActivationInfo";
        $activationInfo = $this->deserializeData($type);
        $this->activation->activate($activationInfo);

        $username = $activationInfo->getLogin();
        $user = $this->userManager->findUserByUsername($username);
        $view = $this->view($user);
        return $this->handleView($view);
    }

    /**
     * 
     * @param type $login
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postUserAddRoleAction($login)
    {
        $user = $this->findUser($login);

        $roleInfo = $this->deserializeData($this->rolesInfoNamespace);

        $this->roleManager->addRolesToUser($user, $roleInfo);

        $view = $this->view($user);
        return $this->handleView($view);
    }

    public function postUserRemoveRoleAction($login)
    {
        $user = $this->findUser($login);

        $roleInfo = $this->deserializeData($this->rolesInfoNamespace);

        $this->roleManager->removeRolesFromUser($user, $roleInfo);

        $view = $this->view($user);
        return $this->handleView($view);
    }

    public function postUserAthenticateAction()
    {
        $type = "Qwer\UserBundle\Entity\AuthenticationInfo";
        $info = $this->deserializeData($type);

        $user = $this->auth->authenticate($info);

        $view = $this->view($user);
        return $this->handleView($view);
    }

    public function postUserGroupAddAction($login, $groupId)
    {
        $user = $this->findUser($login);
        
        $type = "Qwer\UserBundle\Entity\AuthenticationInfo";
        $info = $this->deserializeData($type);
        
        $this->auth->authenticate($info);
        
        $this->userManager->addUserToGroup($user, $groupId);
        
        $view = $this->view($user);
        return $this->handleView($view);
    }
    
    public function postUserGroupRemoveAction($login, $groupId)
    {
        $user = $this->findUser($login);
        
        $type = "Qwer\UserBundle\Entity\AuthenticationInfo";
        $info = $this->deserializeData($type);
        
        $this->auth->authenticate($info);
        
        $this->userManager->removeUserFromGroup($user, $groupId);
        
        $view = $this->view($user);
        return $this->handleView($view);
    }

    private function findUser($login)
    {
        $user = $this->userManager->findUserByUsername($login);
        if (!$user) {
            $message = sprintf($this->userNotFoundMessage, $login);
            throw new ResourceNotFoundException($message);
        }
        return $user;
    }

}