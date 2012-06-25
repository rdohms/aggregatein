<?php
namespace Agg\Service\Cache;

/**
 *
 */
class WinCacheDriver implements CacheDriverInterface
{
    /**
     *
     * @param $id
     * @return mixed|null
     */
    public function get($id)
    {
        $success = null;
        $result = wincache_ucache_get($id, $success);

        return $success? $result : null;
    }

    /**
     *
     * @param $id
     * @param $content
     * @param $ttl
     * @return bool
     */
    public function set($id, $content, $ttl)
    {
        return wincache_ucache_set($id, $content, $ttl);
    }

}
