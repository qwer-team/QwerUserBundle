<?php

namespace Qwer\UserBundle\Service;

use Qwer\UserBundle\Entity\User;
use Qwer\UserBundle\Entity\Token;

class TokenHashGenerator
{

    public function generate(User $user, Token $token)
    {
        $username = $user->getUsername();
        $pass = $user->getPassword();
        $externalId = $token->getId();
        $date = date("Y-m-d#H:i:s");
        
        $string = sprintf("%s%s%s%s", $username, $pass, $externalId, $date);

        $hash = sha1($string);
        return $hash;
    }

}