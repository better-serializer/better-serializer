<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use Doctrine\Common\Cache\Cache;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class CachedProcessorFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory
 */
final class CachedProcessorFactory extends AbstractProcessorFactory implements ProcessorFactoryInterface
{

    /**
     * @var Cache
     */
    private $cache;

    private const CACHE_KEY_PREFIX = 'writer||processor||';

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
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     * @throws LogicException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function createFromType(TypeInterface $type, SerializationContextInterface $context): ProcessorInterface
    {
        $cacheKey = $this->getCacheKey($type, $context);

        if ($this->cache->contains($cacheKey)) {
            return $this->cache->fetch($cacheKey);
        }

        $processor = parent::createFromType($type, $context);
        $this->cache->save($cacheKey, $processor);

        return $processor;
    }

    /**
     * @param TypeInterface $type
     * @param SerializationContextInterface $context
     * @return string
     */
    private function getCacheKey(TypeInterface $type, SerializationContextInterface $context): string
    {
        return self::CACHE_KEY_PREFIX . $type . '||' . json_encode($context->getGroups());
    }
}
