<?php

namespace Qwer\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class QwerUserBundle extends Bundle
{

    public function getParent()
    {
        'FOSUserBundle';
    }

}
