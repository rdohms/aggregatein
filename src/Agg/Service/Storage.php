<?php
namespace Agg\Service;

use WindowsAzure\Common\Configuration;
use WindowsAzure\Table\TableService;
use WindowsAzure\Table\TableSettings;
use WindowsAzure\Table\Models\Entity;
use WindowsAzure\Table\Models\EdmType;
use WindowsAzure\Common\ServiceException;

/**
 * Handles storage to Azure Tables
 *
 * @todo add pk/rk method to aggregation and abstract away the load/store process
 * @todo rename this to AggregationStorage?
 */
class Storage
{

    const TABLE_AGGREGATION = 'tbaggregation';

    /**
     * @var \WindowsAzure\Table\TableRestProxy
     */
    protected $tableProxy;

    /**
     * @param array $config
     */
    public function __construct($config)
    {
        $configObject = new Configuration();
        $configObject->setProperty(TableSettings::ACCOUNT_NAME, $config['account']);
        $configObject->setProperty(TableSettings::ACCOUNT_KEY, $config['key']);
        $configObject->setProperty(TableSettings::URI, "http://{$config['account']}.table.core.windows.net/");

        $this->tableProxy = TableService::create($configObject);
        $this->assertTables();

    }

    /**
     * Assert table exists in Storage
     *
     * @return mixed
     */
    protected function assertTables()
    {
        try {
            // Create table.
            $this->tableProxy->createTable(self::TABLE_AGGREGATION);
        }
        catch(ServiceException $e){

            if ($e->getCode() == 409) return;

            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
        }
    }

    /**
     * Store an Aggregation in DB
     *
     * @param \Agg\Entity\Aggregation $aggregation
     */
    public function storeAggregation($aggregation)
    {
        //Generate unique slug
        $slug = $this->getUniqueSlug($this->slugify($aggregation->getTitle()));
        $aggregation->setSlug($slug);

        $entity = new Entity();
        $entity->setPartitionKey("talk");
        $entity->setRowKey($slug);
        $entity->addProperty("slug", EdmType::STRING, $aggregation->getSlug());
        $entity->addProperty("title", EdmType::STRING, $aggregation->getTitle());
        $entity->addProperty("talks", EdmType::STRING, serialize($aggregation->getTalks()));
        $entity->addProperty("speakerName", EdmType::STRING, $aggregation->getSpeakerName());
        $entity->addProperty("speakerUrl", EdmType::STRING, $aggregation->getSpeakerUrl());

        try{
            $this->tableProxy->insertEntity(self::TABLE_AGGREGATION, $entity);
        }
        catch(ServiceException $e){
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
        }

    }

    /**
     * Retrieves an aggregation by its slug
     *
     * @param $slug
     * @return \Agg\Entity\Aggregation|bool
     */
    public function retrieveAggregationBySlug($slug)
    {
        $filter = "slug eq '$slug'";

        try {

            $result = $this->tableProxy->queryEntities(self::TABLE_AGGREGATION, $filter);

            $entities = $result->getEntities();
            if (count($entities) == 0) {
                return false;
            }

            /** @var $entity Entity */
            $entity = array_shift($entities);

            $agg = new \Agg\Entity\Aggregation();
            $agg->setSlug($entity->getPropertyValue('slug'));
            $agg->setTitle($entity->getPropertyValue('title'));
            $agg->setTalks(unserialize(htmlspecialchars_decode($entity->getPropertyValue('talks'))));
            $agg->setSpeakerName($entity->getPropertyValue('speakerName'));
            $agg->setSpeakerUrl($entity->getPropertyValue('speakerUrl'));

            return $agg;

        }catch(ServiceException $e){
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
            return false;
        }
    }

    /**
     * Retrieves a list of all aggregations
     *
     * @return array|bool
     */
    public function retrieveAggregationList()
    {
        try {

            $result = $this->tableProxy->queryEntities(self::TABLE_AGGREGATION);

            $entities = $result->getEntities();

            $aggregations = array();
            foreach ($entities as $entity) {

                /** @var $entity Entity */

                $agg = new \Agg\Entity\Aggregation();
                $agg->setSlug($entity->getPropertyValue('slug'));
                $agg->setTitle($entity->getPropertyValue('title'));
                $agg->setTalks(unserialize(htmlspecialchars_decode($entity->getPropertyValue('talks'))));
                $agg->setSpeakerName($entity->getPropertyValue('speakerName'));
                $agg->setSpeakerUrl($entity->getPropertyValue('speakerUrl'));

                $aggregations[] = $agg;
            }

            return $aggregations;

        }catch(ServiceException $e){
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
            return false;
        }
    }

    /**
     * Gets a unique slug by chacking storage
     *
     * @param $slug
     * @return bool|string
     */
    public function getUniqueSlug($slug)
    {
        $filter = "slug eq '$slug'";

        try {

            $result = $this->tableProxy->queryEntities(self::TABLE_AGGREGATION, $filter);

            $entities = $result->getEntities();
            var_dump($entities, count($entities));
            if (count($entities) > 0) {
                $slug = $slug . rand(0,9);
                var_dump($slug);
                return $this->getUniqueSlug($slug);
            }

            return $slug;

        }catch(ServiceException $e){
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
            return false;
        }
    }

    /**
     * Cleans a string for slug format
     *
     * @param $text
     * @return mixed|string
     */
    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }
}