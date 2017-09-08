<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use Doctrine\Common\Cache\Cache;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class CachedProcessorFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory
 */
final class CachedProcessorFactory extends AbstractProcessorFactory implements ProcessorFactoryInterface
{

    /**
     * @var Cache
     */
    private $cache;

    private const CACHE_KEY_PREFIX = 'reader||processor||';

    /**
     * CachedProcessorFactory constructor.
     * @param ProcessorFactoryInterface $processorFactory
     * @param Cache $cache
     */
    public function __construct(ProcessorFactoryInterface $processorFactory, Cache $cache)
    {
        parent::__construct($processorFactory);
        $this->cache = $cache;
    }

    /**
     * @param TypeInterface $type
     * @return ProcessorInterface
     * @throws LogicException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function createFromType(TypeInterface $type): ProcessorInterface
    {
        $cacheKey = $this->getCacheKey($type);

        if ($this->cache->contains($cacheKey)) {
            return $this->cache->fetch($cacheKey);
        }

        $processor = parent::createFromType($type);
        $this->cache->save($cacheKey, $processor);

        return $processor;
    }

    /**
     * @param TypeInterface $type
     * @return string
     */
    private function getCacheKey(TypeInterface $type): string
    {
        return self::CACHE_KEY_PREFIX . $type;
    }
}
