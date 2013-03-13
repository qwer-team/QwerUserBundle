<?php

/**
 * Rest user controller.
 * @author Roman Wolosovski <rasom@ukr.net>
 */

namespace Qwer\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use JMS\Serializer\Serializer;
use JMS\DiExtraBundle\Annotation as DI;
use Qwer\UserBundle\Entity\Users;

/**
 * Provides methods for users' management in system.
 */
class UserController extends RestController
{

    /**
     * User manager.
     * Extends fos.user_manager.
     * @DI\Inject("qwer.user_manager")
     * @var \Qwer\UserBundle\Service\UserManager
     */
    public $userManager;

    /**
     * Registration service.
     * @DI\Inject("user.registration")
     * @var \Qwer\UserBundle\Service\UserRegistration 
     */
    public $registration;

    /**
     * Activation service.
     * @DI\Inject("user.activation")
     * @var \Qwer\UserBundle\Service\UserActivation  
     */
    public $activation;

    /**
     * Role manager.
     * Provides methods for manipulations with roles.
     * @DI\Inject("role_manager")
     * @var \Qwer\UserBundle\Service\RoleManager
     */
    public $roleManager;

    /**
     * Entity manager.
     * @DI\Inject("doctrine.orm.entity_manager")
     * @var \Doctrine\ORM\EntityManager 
     */
    public $em;

    /**
     * Exception message.
     * @DI\Inject("qwer.user_manager")
     * @var string 
     */
    private $userNotFoundMessage = "User %s was not found.";

    /**
     * Authentication service.
     * @DI\Inject("qwer.auth")
     * @var \Qwer\UserBundle\Service\Authentication 
     */
    private $auth;

    /**
     * Namespace of roles info entity. 
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
     * Information about user.  
     * Инфа по конкретному пользователю.<br />
     * Method url: /users/{login}.{_format}.<br />
     * Response examples: <br />
     * 1) json
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
     * }
     * </pre>
     * 2) xml
     * <pre>
     * &lt;result&gt;
     *  &lt;status&gt;&lt;![CDATA[success]]&gt;&lt;/status&gt;
     *  &lt;data&gt;
     *    &lt;id&gt;1&lt;/id&gt;
     *    &lt;username&gt;&lt;![CDATA[rasom]]&gt;&lt;/username&gt;
     *    &lt;email&gt;&lt;![CDATA[rasom@ukr.net]]&gt;&lt;/email&gt;
     *    &lt;enabled&gt;true&lt;/enabled&gt;
     *    &lt;last_login&gt;&lt;![CDATA[2013-03-06T15:48:46+0200]]&gt;&lt;/last_login&gt; 
     *    &lt;groups&gt;
     *      &lt;group&gt;
     *        &lt;id&gt;1&lt;/id&gt;
     *        &lt;name&gt;&lt;![CDATA[admin]]&gt;&lt;/name&gt;
     *        &lt;roles&gt;
     *          &lt;role&gt;&lt;![CDATA[ROLE_ADMIN]]&gt;&lt;/role&gt;
     *        &lt;/roles&gt;
     *        &lt;description&gt;&lt;![CDATA[]]&gt;&lt;/description&gt;
     *      &lt;/group&gt;
     *    &lt;/groups&gt;
     *    &lt;locked&gt;false&lt;/locked&gt;
     *    &lt;expired&gt;false&lt;/expired&gt;
     *    &lt;credentials_expired&gt;false&lt;/credentials_expired&gt;
     *  &lt;/data&gt;
     * &lt;/result&gt;
     * </pre>
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
    public function postUsersActivateAction()
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
     * Adds roles to user.
     * Method url: /users/{login}/add/role.{_format}
     * Запрос:<pre>
     * &lt;?xml version="1.0" encoding="UTF-8"?&gt;
     * &lt;request&gt;
     *    &lt;roles&gt;
     *        &lt;role&gt;<b>10&lt;!--roleId--&gt;</b>&lt;/role&gt;
     *        &lt;role&gt;<b>15&lt;!--roleId--&gt;</b>&lt;/role&gt;
     *        ...
     *    &lt;/roles&gt;
     *  &lt;authentication&gt;
     *      &lt;login&gt;<b>rasom</b>&lt;/login&gt;
     *      &lt;password&gt;<b>123</b>&lt;/password&gt;
     *  &lt;/authentication&gt;
     * &lt;/request&gt;
     * </pre>
     * Ответ такой же как иприпросмотре инфы по юзеру.
     * @param string $login
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

    /**
     * Removes roles to user.
     * Method url: /users/{login}/add/role.{_format} <br />
     * Запрос:
     * <pre>
     * &lt;?xml version="1.0" encoding="UTF-8"?&gt;
     * &lt;request&gt;
     *    &lt;roles&gt;
     *        &lt;role&gt;<b>10&lt;!--roleId--&gt;</b>&lt;/role&gt;
     *        &lt;role&gt;<b>15&lt;!--roleId--&gt;</b>&lt;/role&gt;
     *        ...
     *    &lt;/roles&gt;
     *  &lt;authentication&gt;
     *      &lt;login&gt;<b>rasom</b>&lt;/login&gt;
     *      &lt;password&gt;<b>123</b>&lt;/password&gt;
     *  &lt;/authentication&gt;
     * &lt;/request&gt;
     * </pre>
     * Ответ такой же как иприпросмотре инфы по юзеру.
     * @param string $login
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postUserRemoveRoleAction($login)
    {
        $user = $this->findUser($login);

        $roleInfo = $this->deserializeData($this->rolesInfoNamespace);

        $this->roleManager->removeRolesFromUser($user, $roleInfo);

        $view = $this->view($user);
        return $this->handleView($view);
    }

    /**
     * Check user auth data.
     * Method url: /users/athenticate.{_format} <br />
     * Request:
     * <pre>
     * &lt;?xml version="1.0" encoding="UTF-8"?&gt;
     * &lt;request&gt;
     *      &lt;login&gt;<b>rasom</b>&lt;/login&gt;
     *      &lt;password&gt;<b>123</b>&lt;/password&gt;
     * &lt;/request&gt;
     * </pre>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postUsersAthenticateAction()
    {
        $type = "Qwer\UserBundle\Entity\AuthenticationInfo";
        $info = $this->deserializeData($type);

        $user = $this->auth->authenticate($info);

        $view = $this->view($user);
        return $this->handleView($view);
    }

    /**
     * Adds user to group.
     * Method url: /users/{login}/groups/{groupId}/add.{_format} <br />
     * Request's data needs to contain auth info:
     * <pre>
     * &lt;?xml version="1.0" encoding="UTF-8"?&gt;
     * &lt;request&gt;
     *      &lt;login&gt;<b>rasom</b>&lt;/login&gt;
     *      &lt;password&gt;<b>123</b>&lt;/password&gt;
     * &lt;/request&gt;
     * </pre>
     * @param string $login
     * @param integer $groupId
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Removes user from group.
     * Method url: /users/{login}/groups/{groupId}/remove.{_format} <br />
     * Request's data needs to contain auth info:
     * <pre>
     * &lt;?xml version="1.0" encoding="UTF-8"?&gt;
     * &lt;request&gt;
     *      &lt;login&gt;<b>rasom</b>&lt;/login&gt;
     *      &lt;password&gt;<b>123</b>&lt;/password&gt;
     * &lt;/request&gt;
     * </pre>
     * @param string $login
     * @param integer $groupId
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Finds user by login.
     * @param string $login
     * @return \Qwer\UserBundle\Entity\User
     * @throws ResourceNotFoundException
     */
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