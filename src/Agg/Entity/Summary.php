<?php
namespace Agg\Entity;

class Summary
{

    /**
     * @var array
     */
    protected $talks;

    /**
     * @var Stats
     */
    protected $stats;

    /**
     * @var array
     */
    protected $graphData;

    /**
     * @var array
     */
    protected $graphDates;

    /**
     * @var array
     */
    protected $graphRatings;

    /**
     * @param array $graphData
     */
    public function setGraphData($graphData)
    {
        $this->graphData = $graphData;

        $this->setGraphRatings( array_values($graphData) );

        $keys = array_keys($graphData);
        $dates = array_map(function($t) { return date('jS F, Y', $t); }, $keys);

       $this->setGraphDates($dates);
    }

    /**
     * @return array
     */
    public function getGraphData()
    {
        return $this->graphData;
    }

    /**
     * @param \Agg\Entity\Stats $stats
     */
    public function setStats($stats)
    {
        $this->stats = $stats;
    }

    /**
     * @return \Agg\Entity\Stats
     */
    public function getStats()
    {
        return $this->stats;
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
     * @param array $graphDates
     */
    public function setGraphDates($graphDates)
    {
        $this->graphDates = $graphDates;
    }

    /**
     * @return array
     */
    public function getGraphDates()
    {
        return $this->graphDates;
    }

    /**
     * @param array $graphRatings
     */
    public function setGraphRatings($graphRatings)
    {
        $this->graphRatings = $graphRatings;
    }

    /**
     * @return array
     */
    public function getGraphRatings()
    {
        return $this->graphRatings;
    }
}