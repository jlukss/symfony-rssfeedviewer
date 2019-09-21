<?php
namespace App\Service;

use App\Entity\Article;
use Psr\Log\LoggerInterface;

class FeedAggregator {
    const FEED_URL = 'https://www.theregister.co.uk/software/headlines.atom';

    /**
     * Get headlines of articles
     *
     * @return Aricle[]
     */
    public function getHeadlines()
    {
        $headlines = [];

        $feed = $this->getFeed();
        $headlines = $this->parseFeed($feed);

        return $headlines;
    }
    
    /**
     * Load feed XML from URL
     *
     * @return string
     */
    public function getFeed()
    {
        $data = \file_get_contents(this::FEED_URL);

        if (!$data) {
            throw new \Exception('Error retrieving feed: ' . this::FEED_URL);
        }

        return $data;
    }

    /**
     * Parse Article entites out of XML feed string
     *
     * @param string $data
     * @return Aricle[]
     */
    public function parseFeed($data)
    {
        $headlines = [];

        try {
            $feed = new \SimpleXMLElement($data);
        } catch (\Exception $e) {
            throw new \Exception('Parse feed error: ' . $e->getMessage());
        }

        if (isset($feed->entry)) {
            foreach($feed->entry as $entry) {
                $headlines[] = $this->_parseEntry($entry);
            }
        }

        return $headlines;
    }

    private function _parseEntry($entry)
    {
        $headline = new Article();

        $headline->setLink((isset($entry->link))?(string) $entry->link['href']:'');
        $headline->setTitle((isset($entry->title))?(string) $entry->title:'');
        $headline->setSummary((isset($entry->summary))?(string) $entry->summary:'');

        return $headline;
    }
}