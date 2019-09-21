<?php
namespace App\Tests\Service;

use App\Entity\Article;
use App\Service\WordAnalyser;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class WordAnalyserTest extends TestCase {
    public function testExtractWords()
    {
        $analyser = new WordAnalyser();

        $article = new Article();
        $article->setLink('http://go.theregister.com/feed/www.theregister.co.uk/2019/09/18/microsoft_to_improve_azure_networking_with_private_links_to_shared_services/');
        $article->setTitle('Microsoft to improve Azure networking with private links to multi-tenant services');
        $article->setSummary('<h4>Preview of private endpoints (accessible both: in the cloud and on premises)</h4>!</p>');

        $commonWords = ['with', 'to', 'the', 'of'];

        $words = $analyser->extractWords($article, $commonWords);

        $this->assertContains('microsoft', $words); 
        $this->assertContains('azure', $words); 
        $this->assertContains('private', $words);
        foreach($commonWords as $word) {
            $this->assertNotContains($word, $words);
        }
        $this->assertNotContains('h4', $words);
    }

    public function testFindTopWords()
    {
        $analyser = new WordAnalyser();

        $words = [
            'amazon',
            'aws',
            'cloud',
            'better',
            'microsoft',
            'azure',
            'cloud',
            'keep',
            'data',
            'cloud',
            'amazon'
        ];

        $top = $analyser->findTopWords($words);

        reset($top);
        $this->assertEquals('cloud', key($top));
        $this->assertEquals(3, $top['cloud']);
        $this->assertEquals(2, $top['amazon']);
    }
}