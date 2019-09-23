<?php
namespace App\Controller;

use App\Service\WordAnalyser;
use App\Service\FeedAggregator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ViewController extends AbstractController
{
    /**
     * @Route("/feed/get")
     */
    public function getFeed(FeedAggregator $feed, WordAnalyser $analyser)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        try {
            $articles = $feed->getHeadlines();

            $commonWords = file($this->getParameter('kernel.project_dir') . '/data/commonwords.txt', FILE_IGNORE_NEW_LINES);
            
            $words = $analyser->getWords($articles, $commonWords);
        
            return new JsonResponse([
                'headlines' => $articles,
                'words' => $words
            ], 200);
        } catch (\Exception $e) {
            $data = [
                'type' => 'error',
                'message' => $e->getMessage()
            ];

            return new JsonResponse($data, 400);
        }
    }
}
