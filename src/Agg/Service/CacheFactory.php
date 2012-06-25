<?php
namespace Agg\Service;

class CacheFactory
{

    /**
     * @return \Agg\Service\Cache\CacheDriverInterface
     */
    public static function getCache()
    {

        if (function_exists('wincache_ucache_set')) {
            return new \Agg\Service\Cache\WinCacheDriver();
        }

        return new \Agg\Service\Cache\ArrayCacheDriver();

    }

}
