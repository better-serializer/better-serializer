<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\MetaData;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use Doctrine\Common\Cache\Cache;

/**
 * Class CachedContextualReader
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\MetaData
 */
final class CachedContextualReader implements ContextualReaderInterface
{

    /**
     * @const string
     */
    private const CACHE_PREFIX = 'contextual_metadata||';

    /**
     * @var ContextualReaderInterface
     */
    private $reader;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * CachedContextualReader constructor.
     * @param ContextualReaderInterface $reader
     * @param Cache $cache
     */
    public function __construct(ContextualReaderInterface $reader, Cache $cache)
    {
        $this->reader = $reader;
        $this->cache = $cache;
    }

    /**
     * @param string $className
     * @param SerializationContextInterface $context
     * @return MetaDataInterface
     */
    public function read(string $className, SerializationContextInterface $context): MetaDataInterface
    {
        $cacheKey = $this->getCacheKey($className, $context);

        if (!$this->cache->contains($cacheKey)) {
            $metaData = $this->reader->read($className, $context);
            $this->cache->save($cacheKey, $metaData);

            return $metaData;
        }

        return $this->cache->fetch($cacheKey);
    }

    /**
     * @param string $className
     * @param SerializationContextInterface $context
     * @return string
     */
    private function getCacheKey(string $className, SerializationContextInterface $context): string
    {
        return self::CACHE_PREFIX . $className . '||' . json_encode($context->getGroups());
    }
}
