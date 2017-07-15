<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor\Chain;

use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTupleInterface;
use BetterSerializer\DataBind\MetaData\Type\ComplexTypeInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ParamProcessorInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ComplexParamProcessor;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class ComplexParamProcessorFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor
 */
final class ComplexParamProcessorFactory implements ChainedParamProcessorFactoryInterface
{

    /**
     * @var ProcessorFactoryInterface
     */
    private $processorFactory;

    /**
     * ComplexParamProcessorFactory constructor.
     * @param ProcessorFactoryInterface $processorFactory
     */
    public function __construct(ProcessorFactoryInterface $processorFactory)
    {
        $this->processorFactory = $processorFactory;
    }

    /**
     * @param PropertyWithConstructorParamTupleInterface $tuple
     * @return bool
     */
    public function isApplicable(PropertyWithConstructorParamTupleInterface $tuple): bool
    {
        return $tuple->getType() instanceof ComplexTypeInterface;
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

        return new ComplexParamProcessor($tuple->getOutputKey(), $processor);
    }
}
