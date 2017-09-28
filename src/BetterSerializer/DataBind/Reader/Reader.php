<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Reader;

use BetterSerializer\Common\SerializationTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\FqdnStringFormType;
use BetterSerializer\DataBind\Reader\Context\ContextFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class Reader
 * @author mfris
 * @package BetterSerializer\DataBind
 */
final class Reader implements ReaderInterface
{

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * @var ProcessorFactoryInterface
     */
    private $processorFactory;

    /**
     * @var ContextFactoryInterface
     */
    private $contextFactory;

    /**
     * Reader constructor.
     * @param TypeFactoryInterface $typeFactory
     * @param ProcessorFactoryInterface $processorFactory
     * @param ContextFactoryInterface $contextFactory
     */
    public function __construct(
        TypeFactoryInterface $typeFactory,
        ProcessorFactoryInterface $processorFactory,
        ContextFactoryInterface $contextFactory
    ) {
        $this->typeFactory = $typeFactory;
        $this->processorFactory = $processorFactory;
        $this->contextFactory = $contextFactory;
    }

    /**
     * @param string $serialized
     * @param string $stringType
     * @param SerializationTypeInterface $serializationType
     * @return mixed
     * @throws RuntimeException
     * @throws ReflectionException
     * @throws LogicException
     */
    public function readValue(string $serialized, string $stringType, SerializationTypeInterface $serializationType)
    {
        $context = $this->contextFactory->createContext($serialized, $serializationType);
        $typeContext = new FqdnStringFormType($stringType);
        $type = $this->typeFactory->getType($typeContext);
        $processor = $this->processorFactory->createFromType($type);
        $processor->process($context);

        return $context->getDeserialized();
    }
}
