<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use Doctrine\Common\Cache\Cache;
use LogicException;
use ReflectionException;

/**
 * Class CachedReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
final class CachedReader implements ReaderInterface
{

    /**
     * @var ReaderInterface
     */
    private $nestedReader;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @const string
     */
    private const CACHE_PREFIX = 'metaData';

    /**
     * CachedReader constructor.
     * @param ReaderInterface $nestedReader
     * @param Cache $cache
     */
    public function __construct(ReaderInterface $nestedReader, Cache $cache)
    {
        $this->nestedReader = $nestedReader;
        $this->cache = $cache;
    }

    /**
     * @param string $className
     * @return MetaDataInterface
     * @throws LogicException
     * @throws ReflectionException
     */
    public function read(string $className): MetaDataInterface
    {
        $cacheKey = $this->getCacheKey($className);

        if ($this->cache->contains($cacheKey)) {
            return $this->cache->fetch($cacheKey);
        }

        $metaData = $this->nestedReader->read($className);
        $this->cache->save($cacheKey, $metaData);

        return $metaData;
    }

    /**
     * @param string $className
     * @return string
     */
    private function getCacheKey(string $className): string
    {
        return self::CACHE_PREFIX . '||' . $className;
    }
}
