<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain\ChainMemberInterface as MetaDataMember;
use BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\ChainMemberInterface as TypeMember;
use BetterSerializer\DataBind\Reader\Processor\Cached;
use BetterSerializer\DataBind\Reader\Processor\ComplexNestedProcessorInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\Recursive\Cache;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class RecursiveProcessorFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory
 */
final class RecursiveProcessorFactory implements ProcessorFactoryInterface
{

    /**
     * @var ProcessorFactoryInterface
     */
    private $processorFactory;

    /**
     * @var int
     */
    private $nesting = 0;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * RecursiveProcessorFactory constructor.
     * @param ProcessorFactoryInterface $processorFactory
     */
    public function __construct(ProcessorFactoryInterface $processorFactory)
    {
        $this->processorFactory = $processorFactory;
    }

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return ProcessorInterface
     */
    public function createFromMetaData(PropertyMetaDataInterface $metaData): ProcessorInterface
    {
        return $this->processorFactory->createFromMetaData($metaData);
    }

    /**
     * @param TypeInterface $type
     * @return ProcessorInterface
     * @throws ReflectionException|LogicException|RuntimeException
     */
    public function createFromType(TypeInterface $type): ProcessorInterface
    {
        $this->begin();
        $stringCacheKey = (string) $type;
        $processor = $this->getCachedProcessor($stringCacheKey);

        if (!$processor) {
            $processor = $this->processorFactory->createFromType($type);
            $this->storeProcessor($stringCacheKey, $processor);
        }

        $this->commit($processor);

        return $processor;
    }

    /**
     * @param MetaDataMember $chainMember
     */
    public function addMetaDataChainMember(MetaDataMember $chainMember): void
    {
        $this->processorFactory->addMetaDataChainMember($chainMember);
    }

    /**
     * @param TypeMember $chainMember
     */
    public function addTypeChainMember(TypeMember $chainMember): void
    {
        $this->processorFactory->addTypeChainMember($chainMember);
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

        $this->cache->setProcessor($stringCacheKey, new Cached($this->cache, $stringCacheKey));

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
     * @return void
     */
    private function begin(): void
    {
        if ($this->nesting === 0) {
            $this->cache = new Cache();
        }

        $this->nesting++;
    }

    /**
     * @param ProcessorInterface $processor
     * @return void
     */
    private function commit(ProcessorInterface $processor): void
    {
        $this->nesting--;

        if ($this->nesting === 0 && $processor instanceof ComplexNestedProcessorInterface) {
            $processor->resolveRecursiveProcessors();
        }
    }
}
