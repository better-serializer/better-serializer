<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\ChainMemberInterface as MetaDataMember;
use BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ChainMemberInterface as TypeMember;
use BetterSerializer\DataBind\Writer\Processor\Cached;
use BetterSerializer\DataBind\Writer\Processor\ComplexNestedProcessorInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\Recursive\Cache;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class RecursiveProcessorFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory
 */
final class RecursiveProcessorFactory implements ProcessorFactoryInterface
{

    /**
     * @var ProcessorFactoryInterface
     */
    private $processorFactory;

    /**
     * @var array
     */
    private $nestings = [];

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
        $stringCacheKey = (string) $type;
        $this->begin($stringCacheKey);
        $processor = $this->getCachedProcessor($stringCacheKey);

        if (!$processor) {
            $processor = $this->processorFactory->createFromType($type);
            $this->storeProcessor($stringCacheKey, $processor);
        }

        $this->commit($stringCacheKey, $processor);

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

        if ($this->nestings[$key] === 0 && $processor instanceof ComplexNestedProcessorInterface) {
            $processor->resolveRecursiveProcessors();
            unset($this->nestings[$key]);
        }
    }
}
