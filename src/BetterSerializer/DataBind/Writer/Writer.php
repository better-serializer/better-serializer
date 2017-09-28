<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Writer;

use BetterSerializer\Common\SerializationTypeInterface;
use BetterSerializer\DataBind\Writer\Context\ContextFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Type\ExtractorInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class Writer
 * @author mfris
 * @package BetterSerializer\DataBind
 */
final class Writer implements WriterInterface
{

    /**
     * @var ExtractorInterface
     */
    private $typeExtractor;

    /**
     * @var ProcessorFactoryInterface
     */
    private $processorFactory;

    /**
     * @var ContextFactoryInterface
     */
    private $contextFactory;

    /**
     * Writer constructor.
     * @param ExtractorInterface $typeExtractor
     * @param ProcessorFactoryInterface $processorFactory
     * @param ContextFactoryInterface $contextFactory
     */
    public function __construct(
        ExtractorInterface $typeExtractor,
        ProcessorFactoryInterface $processorFactory,
        ContextFactoryInterface $contextFactory
    ) {
        $this->typeExtractor = $typeExtractor;
        $this->processorFactory = $processorFactory;
        $this->contextFactory = $contextFactory;
    }

    /**
     * @param mixed             $data
     * @param SerializationTypeInterface $serializationType
     * @param SerializationContextInterface $context
     * @return string
     * @throws RuntimeException
     * @throws ReflectionException
     * @throws LogicException
     */
    public function writeValueAsString(
        $data,
        SerializationTypeInterface $serializationType,
        SerializationContextInterface $context
    ): string {
        $typeContext = $this->contextFactory->createContext($serializationType);
        $type = $this->typeExtractor->extract($data);
        $processor = $this->processorFactory->createFromType($type, $context);
        $processor->process($typeContext, $data);

        return $typeContext->getData();
    }
}
