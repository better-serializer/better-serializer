<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Reader;

use BetterSerializer\Common\SerializationTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\StringTypeParserInterface;
use BetterSerializer\DataBind\Reader\Context\ContextFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 *
 */
final class Reader implements ReaderInterface
{

    /**
     * @var StringTypeParserInterface
     */
    private $stringTypeParser;

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
     * @param StringTypeParserInterface $stringTypeParser
     * @param TypeFactoryInterface $typeFactory
     * @param ProcessorFactoryInterface $processorFactory
     * @param ContextFactoryInterface $contextFactory
     */
    public function __construct(
        StringTypeParserInterface $stringTypeParser,
        TypeFactoryInterface $typeFactory,
        ProcessorFactoryInterface $processorFactory,
        ContextFactoryInterface $contextFactory
    ) {
        $this->stringTypeParser = $stringTypeParser;
        $this->typeFactory = $typeFactory;
        $this->processorFactory = $processorFactory;
        $this->contextFactory = $contextFactory;
    }

    /**
     * @param $serialized
     * @param string $typeString
     * @param SerializationTypeInterface $serializationType
     * @return mixed
     * @throws LogicException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function readValue($serialized, string $typeString, SerializationTypeInterface $serializationType)
    {
        $context = $this->contextFactory->createContext($serialized, $serializationType);
        $stringType = $this->stringTypeParser->parseSimple($typeString);
        $type = $this->typeFactory->getType($stringType);
        $processor = $this->processorFactory->createFromType($type);
        $processor->process($context);

        return $context->getDeserialized();
    }
}
