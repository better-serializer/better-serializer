<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTupleInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\InstantiatorFactoryInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor\ParamProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Instantiator\InstantiatorInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\StandardInstantiator;
use ReflectionClass;
use ReflectionException;

/**
 * Class StandardConstructorFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Factory
 */
final class StandardInstantiatorFactory implements InstantiatorFactoryInterface
{

    /**
     * @var ParamProcessorFactoryInterface
     */
    private $paramProcessorFactory;

    /**
     * StandardInstantiatorFactory constructor.
     * @param ParamProcessorFactoryInterface $processorFactory
     */
    public function __construct(ParamProcessorFactoryInterface $processorFactory)
    {
        $this->paramProcessorFactory = $processorFactory;
    }

    /**
     * @param MetaDataInterface $metaData
     * @return InstantiatorInterface
     * @throws ReflectionException
     */
    public function newInstantiator(MetaDataInterface $metaData): InstantiatorInterface
    {
        $className = $metaData->getClassMetadata()->getClassName();
        $reflClass = new ReflectionClass($className);
        $processorFactory = $this->paramProcessorFactory;
        $paramProcessors = array_map(
            function (PropertyWithConstructorParamTupleInterface $tuple) use ($processorFactory) {
                return $processorFactory->newParamProcessor($tuple);
            },
            $metaData->getPropertyWithConstructorParamTuples()
        );

        return new StandardInstantiator($reflClass, $paramProcessors);
    }

    /**
     * @param MetaDataInterface $metaData
     * @return bool
     */
    public function isApplicable(MetaDataInterface $metaData): bool
    {
        return $metaData->isInstantiableByConstructor();
    }
}
