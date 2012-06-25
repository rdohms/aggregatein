<?php
namespace Agg\Service\Cache;

interface CacheDriverInterface
{
    /**
     * @param $id
     */
    public function get($id);

    /**
     * @param $id
     * @param $content
     * @param $ttl
     */
    public function set($id, $content, $ttl);

}
