<?php
namespace App\Entity;

class Article {
    private $link;

    private $title;

    private $summary;

    /**
     * Setter for Article link
     *
     * @param string $value
     * @return void
     */
    public function setLink($value) {
        $this->link = $value;
    }
    
    /**
     * Getter for Article link
     *
     * @return string
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * Setter for Article title
     *
     * @param string $value
     * @return void
     */
    public function setTitle($value) {
        $this->title = $value;
    }
    
    /**
     * Getter for Article title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Setter for Article summary
     *
     * @param string $value
     * @return void
     */
    public function setSummary($value) {
        $this->summary = $value;
    }
    
    /**
     * Getter for Article summary
     *
     * @return string
     */
    public function getSummary() {
        return $this->summary;
    }

}