<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController {
    /**
     * @Route("/user/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $data = [
            'type' => 'error',
            'message' => $error->getMessage()
        ];

        return new JsonResponse($data, 403);
    }

    /**
     * @Route("/user/logout")
     */
    public function logout()
    {
        return new Response();
    }
}