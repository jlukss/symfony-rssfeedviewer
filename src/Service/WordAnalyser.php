<?php
namespace App\Service;

use App\Entity\Article;

class WordAnalyser
{
    const WORDS_TOP = 10;

    /**
     * Get top most popular words
     *
     * @param Article[] $headlines
     * @return array
     */
    public function getWords($headlines, $commonWords = [])
    {
        $words = [];
        
        foreach ($headlines as $headline) {
            $words = \array_merge($words, $this->extractWords($headline, $commonWords));
        }

        $top = $this->findTopWords($words);

        return \array_slice($top, 0, $this::WORDS_TOP, true);
    }
    
    /**
     * Extract words from Article feed
     *
     * @param Article $headline
     * @param string[] $commonWords
     * @return string[]
     */
    public function extractWords($headline, $commonWords)
    {
        $text = strtolower(strip_tags($headline->getTitle() . ' ' . $headline->getSummary()));

        preg_match_all('/[\w]{2,}/', $text, $tokens);

        $words = array_diff($tokens[0], $commonWords);
        
        return $words;
    }

    /**
     * Return words and their count in descending order
     *
     * @param string[] $words
     * @return array
     */
    public function findTopWords($words)
    {
        $count = array_count_values($words);

        arsort($count, SORT_NUMERIC);

        return $count;
    }
}
