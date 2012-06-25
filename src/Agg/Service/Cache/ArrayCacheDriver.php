<?php
namespace Agg\Service\Cache;

/**
 * Simple Array cache class
 */
class ArrayCacheDriver implements CacheDriverInterface
{

    /**
     * @var array
     */
    protected static $data;

    /**
     * Retrieves from ArrayCache
     * @param $id
     * @return mixed|null
     */
    public function get($id)
    {
        if (!is_array(self::$data)) self::$data = array();

        if ( ! array_key_exists($id, self::$data)) {
            return null;
        }

        $data = self::$data[$id];

        if ($data['ttl'] < time()) {
            return null;
        }

        return $data['content'];
    }

    /**
     * @param $id
     * @param $content
     * @param $ttl
     */
    public function set($id, $content, $ttl)
    {
        if (!is_array(self::$data)) self::$data = array();

        $data = array(
            'content' => $content,
            'ttl'     => time() + $ttl,
        );

        self::$data[$id] = $content;
    }

}
