<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\CachedProcessor;
use BetterSerializer\DataBind\Writer\Processor\PropertyProcessorInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\Recursive\Cache;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 *
 */
final class RecursiveProcessorFactory extends AbstractProcessorFactory implements ProcessorFactoryInterface
{

    /**
     * @var array
     */
    private $nestings = [];

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @param TypeInterface $type
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     * @throws ReflectionException|LogicException|RuntimeException
     */
    public function createFromType(TypeInterface $type, SerializationContextInterface $context): ProcessorInterface
    {
        $stringCacheKey = $this->getCacheKey($type, $context);
        $this->begin($stringCacheKey);
        $processor = $this->getCachedProcessor($stringCacheKey);

        if (!$processor) {
            $processor = parent::createFromType($type, $context);
            $this->storeProcessor($stringCacheKey, $processor);
        }

        $this->commit($stringCacheKey, $processor);

        return $processor;
    }

    /**
     * @param string $stringCacheKey
     * @return ProcessorInterface|null
     */
    private function getCachedProcessor(string $stringCacheKey): ?ProcessorInterface
    {
        $processor = $this->cache->getProcessor($stringCacheKey);

        if ($processor) {
            return $processor;
        }

        $this->cache->setProcessor($stringCacheKey, new CachedProcessor($this->cache, $stringCacheKey));

        return null;
    }

    /**
     * @param string $stringCacheKey
     * @param ProcessorInterface $processor
     */
    private function storeProcessor(string $stringCacheKey, ProcessorInterface $processor): void
    {
        $this->cache->setProcessor($stringCacheKey, $processor);
    }

    /**
     * @param string $key
     * @return void
     */
    private function begin(string $key): void
    {
        if (empty($this->nestings)) {
            $this->cache = new Cache();
        }

        if (!isset($this->nestings[$key])) {
            $this->nestings[$key] = 0;
        }

        $this->nestings[$key]++;
    }

    /**
     * @param string $key
     * @param ProcessorInterface $processor
     * @return void
     */
    private function commit(string $key, ProcessorInterface $processor): void
    {
        $this->nestings[$key]--;

        if ($this->nestings[$key] === 0 && $processor instanceof PropertyProcessorInterface) {
            $processor->resolveRecursiveProcessors();
            unset($this->nestings[$key]);
        }
    }

    /**
     * @param TypeInterface $type
     * @param SerializationContextInterface $context
     * @return string
     */
    private function getCacheKey(TypeInterface $type, SerializationContextInterface $context): string
    {
        return $type . '||' . json_encode($context->getGroups());
    }
}
