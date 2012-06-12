<?php
namespace Agg\Entity;

class Talk
{

    /**
     * @var string
     */
    protected $title;

    /**
     * @var int
     */
    protected $rating;

    /**
     * @var int
     */
    protected $commentCounts;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $eventName;

    /**
     * @var string
     */
    protected $eventUrl;

    /**
     * @param int $commentCounts
     */
    public function setCommentCounts($commentCounts)
    {
        $this->commentCounts = $commentCounts;
    }

    /**
     * @return int
     */
    public function getCommentCounts()
    {
        return $this->commentCounts;
    }

    /**
     * @param string $eventName
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;
    }

    /**
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @param string $eventUrl
     */
    public function setEventUrl($eventUrl)
    {
        $this->eventUrl = $eventUrl;
    }

    /**
     * @return string
     */
    public function getEventUrl()
    {
        return $this->eventUrl;
    }

    /**
     * @param int $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}