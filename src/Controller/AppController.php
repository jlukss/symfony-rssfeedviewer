<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app")
     * @Route("/register")
     * @Route("/viewer")
     */
    public function login()
    {
        return $this->render('app.html.twig');
    }
}
