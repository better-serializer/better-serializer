<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor\Chain;

use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTupleInterface;
use BetterSerializer\DataBind\MetaData\Type\ComplexTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\ExtensionTypeInterface;
use BetterSerializer\DataBind\Naming\PropertyNameTranslator\TranslatorInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ParamProcessorInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ComplexParamProcessor;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 *
 */
final class ComplexParamProcessorFactory implements ChainedParamProcessorFactoryInterface
{

    /**
     * @var ProcessorFactoryInterface
     */
    private $processorFactory;

    /**
     * @var TranslatorInterface
     */
    private $nameTranslator;

    /**
     * @param ProcessorFactoryInterface $processorFactory
     * @param TranslatorInterface $nameTranslator
     */
    public function __construct(ProcessorFactoryInterface $processorFactory, TranslatorInterface $nameTranslator)
    {
        $this->processorFactory = $processorFactory;
        $this->nameTranslator = $nameTranslator;
    }

    /**
     * @param PropertyWithConstructorParamTupleInterface $tuple
     * @return bool
     */
    public function isApplicable(PropertyWithConstructorParamTupleInterface $tuple): bool
    {
        $type = $tuple->getType();

        return $type instanceof ComplexTypeInterface || $type instanceof ExtensionTypeInterface;
    }

    /**
     * @param PropertyWithConstructorParamTupleInterface $tuple
     * @return ParamProcessorInterface
     * @throws LogicException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function newChainedParamProcessorFactory(
        PropertyWithConstructorParamTupleInterface $tuple
    ): ParamProcessorInterface {
        $processor = $this->processorFactory->createFromType($tuple->getType());
        $serializationName = $this->nameTranslator->translate($tuple->getPropertyMetaData());

        return new ComplexParamProcessor($serializationName, $processor);
    }
}
