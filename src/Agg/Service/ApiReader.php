<?php
namespace Agg\Service;

class ApiReader
{

    public function getTalkData($input)
    {
        $url = $this->extractUrl("http://api.joind.in/v2.1/talks/{id}", $input);
        $data = $this->getData($url);

        if (!$data) return null;

        return array_shift($data->talks);
    }

    public function getEventData($input)
    {
        $url = $this->extractUrl("http://api.joind.in/v2.1/events/{id}", $input);
        $data = $this->getData($url);

        if (!$data) return null;

        return array_shift($data->events);
    }

    public function getSpeakerData($input)
    {
        $url = $this->extractUrl("http://api.joind.in/v2.1/users/{id}", $input);
        $data = $this->getData($url);

        if (!$data) return null;

        return array_shift($data->users);
    }

    protected function getData($url)
    {
        $cacheKey = md5($url);

        //check cache

        //get raw
        $json = file_get_contents($url);
        $data = json_decode($json);

        //save to cache

        //return
        return $data;
    }

    public function extractUrl($base, $input)
    {
        if (strpos($input, 'http://') === false) {
            return str_replace("{id}", $input, $base);
        }

        return $input;

    }

}