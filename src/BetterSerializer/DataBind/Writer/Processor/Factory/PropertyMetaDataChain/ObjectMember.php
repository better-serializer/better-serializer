<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface as ExtractorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\ObjectProcessorInterface;
use BetterSerializer\DataBind\Writer\Processor\ObjectProperty;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class SimpleMember
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain
 */
final class ObjectMember extends ExtractingChainMember
{

    /**
     * @var ProcessorFactoryInterface
     */
    private $processorFactory;

    /**
     * ObjectMember constructor.
     * @param ProcessorFactoryInterface $processorFactory
     * @param ExtractorFactoryInterface $extractorFactory
     */
    public function __construct(
        ProcessorFactoryInterface $processorFactory,
        ExtractorFactoryInterface $extractorFactory
    ) {
        parent::__construct($extractorFactory);
        $this->processorFactory = $processorFactory;
    }

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return bool
     */
    protected function isCreatable(PropertyMetaDataInterface $metaData): bool
    {
        return $metaData->getType() instanceof ObjectType;
    }

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return ProcessorInterface
     * @throws LogicException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    protected function createProcessor(PropertyMetaDataInterface $metaData): ProcessorInterface
    {
        $extractor = $this->extractorFactory->newExtractor($metaData);
        $objectProcessor = $this->processorFactory->createFromType($metaData->getType());

        if (!$objectProcessor instanceof ObjectProcessorInterface) {
            throw new LogicException("Invalid processor type: '" . get_class($objectProcessor) . "'");
        }

        return new ObjectProperty($extractor, $objectProcessor, $metaData->getOutputKey());
    }
}
