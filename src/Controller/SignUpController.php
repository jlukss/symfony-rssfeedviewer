<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignUpController {
    
    /**
     * @Route("/user/signup")
     */
    public function signUp() {
        return new Response();
    }

    public function validateEmail()
    {
        return new Response('false');
    }
}