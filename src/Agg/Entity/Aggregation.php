<?php

namespace Agg\Entity;

class Aggregation
{
    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $talks;

    /**
     * @var string
     */
    protected $speakerName;

    /**
     * @var string
     */
    protected $speakerUrl;

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param array $talks
     */
    public function setTalks($talks)
    {
        $this->talks = $talks;
    }

    /**
     * @return array
     */
    public function getTalks()
    {
        return $this->talks;
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
     * @param string $speakerName
     */
    public function setSpeakerName($speakerName)
    {
        $this->speakerName = $speakerName;
    }

    /**
     * @return string
     */
    public function getSpeakerName()
    {
        return $this->speakerName;
    }

    /**
     * @param string $speakerUrl
     */
    public function setSpeakerUrl($speakerUrl)
    {
        $this->speakerUrl = $speakerUrl;
    }

    /**
     * @return string
     */
    public function getSpeakerUrl()
    {
        return $this->speakerUrl;
    }
}