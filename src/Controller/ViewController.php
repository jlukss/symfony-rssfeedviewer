<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewController {
    /**
     * @Route("/feed/get")
     */
    public function getFeed()
    {
        return new Response();
    }

    /**
     * @Route("/feed/words")
     */
    public function getWords()
    {
        return new Response();
    }
}