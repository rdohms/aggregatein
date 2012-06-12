<?php
namespace Agg\Entity;

class Stats
{

    protected $ratings = array();
    protected $counts  = array();

    public function addRating($rating)
    {
        $this->ratings[] = $rating;
    }

    public function addCount($count)
    {
        $this->counts[] = $count;
    }

    public function getAverageRating()
    {
        return array_sum($this->ratings) / count($this->ratings);
    }

    public function getTotalCount()
    {
        return array_sum($this->counts);
    }
}