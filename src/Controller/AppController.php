<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller {
    /**
     * @Route("/", name="app")
     */
    public function login()
    {
        return $this->render('app.html.twig');
    }
}