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
     * @param array $graphData
     */
    public function setGraphData($graphData)
    {
        $this->graphData = $graphData;
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
}