<?php
namespace Agg\Service;

/**
 * Handles API communication with the Joind.in API
 */
class ApiReader
{
    /**
     * @var \Agg\Service\Cache\CacheDriverInterface
     */
    protected $cache;

    /**
     * @param \Agg\Service\Cache\CacheDriverInterface $cacheDriver
     */
    public function __construct($cacheDriver)
    {
        $this->cache = $cacheDriver;
    }

    /**
     * Gets data for a talk
     *
     * @param string $input id or URI
     * @return mixed|null
     */
    public function getTalkData($input)
    {
        $url = $this->extractUrl("http://api.joind.in/v2.1/talks/{id}", $input);
        $data = $this->getData($url);

        if (!$data) return null;

        return array_shift($data->talks);
    }

    /**
     * Gets data for a Event
     *
     * @param string $input id or URI
     * @return mixed|null
     */
    public function getEventData($input)
    {
        $url = $this->extractUrl("http://api.joind.in/v2.1/events/{id}", $input);
        $data = $this->getData($url);

        if (!$data) return null;

        return array_shift($data->events);
    }

    /**
     * Gets data for a Speaker
     *
     * @param string $input id or URI
     * @return mixed|null
     */
    public function getSpeakerData($input)
    {
        $url = $this->extractUrl("http://api.joind.in/v2.1/users/{id}", $input);
        $data = $this->getData($url);

        if (!$data) return null;

        return array_shift($data->users);
    }

    /**
     * Retrieves data from API or Cache
     *
     * @param string $url
     * @return mixed
     */
    protected function getData($url)
    {
        $cacheKey = md5($url);

        //check cache
        $data = $this->cache->get($cacheKey);

        if ($data === null) {

            //get raw
            $json = file_get_contents($url);
            $data = json_decode($json);

            //save to cache
            $this->cache->set($cacheKey, $data, 86400);
        }


        //return
        return $data;
    }

    /**
     * Gets a valid API URI from input
     *
     * @param string $base
     * @param string $input
     *
     * @return mixed
     */
    public function extractUrl($base, $input)
    {
        if (strpos($input, 'http://') === false) {
            return str_replace("{id}", $input, $base);
        }

        return $input;
    }

}