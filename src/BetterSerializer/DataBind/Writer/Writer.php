<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Writer;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\DataBind\Writer\Context\ContextFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class Writer
 * @author mfris
 * @package BetterSerializer\DataBind
 */
final class Writer
{

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
     * @param ProcessorFactoryInterface $processorFactory
     * @param ContextFactoryInterface $contextFactory
     */
    public function __construct(ProcessorFactoryInterface $processorFactory, ContextFactoryInterface $contextFactory)
    {
        $this->processorFactory = $processorFactory;
        $this->contextFactory = $contextFactory;
    }

    /**
     * @param mixed             $object
     * @param SerializationType $type
     * @return string
     * @throws RuntimeException
     * @throws ReflectionException
     * @throws LogicException
     */
    public function writeValueAsString($object, SerializationType $type): string
    {
        $context = $this->contextFactory->createContext($type);
        $processor = $this->processorFactory->create(get_class($object));
        $processor->process($context, $object);

        return $context->getData();
    }
}
