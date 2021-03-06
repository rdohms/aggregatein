<?php
namespace Agg\Service;

class StatsParser
{
    /**
     * @var ApiReader
     */
    protected $api;

    public function __construct($api)
    {
        $this->api = $api;
    }

    public function parseSummaryStats($inputTalks)
    {
        $summary = new \Agg\Entity\Summary();
        $stats   = new \Agg\Entity\Stats();

        $talks = array();
        $graphData = array();
        foreach($inputTalks as $talkId) {

            $talkData  = $this->api->getTalkData($talkId);
            $eventData = $this->api->getEventData($talkData->event_uri);

            $date = new \DateTime($talkData->start_date);

            $talk = new \Agg\Entity\Talk();

            $talk->setTitle($talkData->talk_title);
            $talk->setUrl($talkData->website_uri);
            $talk->setRating($talkData->average_rating);
            $talk->setCommentCounts($talkData->comment_count);

            if ($eventData != null) {
                $talk->setEventName($eventData->name);
                $talk->setEventUrl($eventData->website_uri);
            }

            $talks[$date->format('U')] = $talk;

            $stats->addRating($talkData->average_rating);
            $stats->addCount($talkData->comment_count);

            $graphData[$date->format('U')] = $talkData->average_rating;
        }

        ksort($graphData);
        ksort($talks);

        $summary->setTalks($talks);
        $summary->setStats($stats);
        $summary->setGraphData($graphData);


        return $summary;
    }

}