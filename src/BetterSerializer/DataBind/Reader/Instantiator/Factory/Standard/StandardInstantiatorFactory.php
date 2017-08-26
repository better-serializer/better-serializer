<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard;

use BetterSerializer\DataBind\MetaData\Model\ExcludePropertiesMetaData;
use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\ChainedInstantiatorFactoryInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\InstantiatorResult;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\InstantiatorResultInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor\ParamProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\StandardInstantiator;
use ReflectionClass;
use ReflectionException;

/**
 * Class StandardConstructorFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Converter
 */
final class StandardInstantiatorFactory implements ChainedInstantiatorFactoryInterface
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
     * @return InstantiatorResultInterface
     * @throws ReflectionException
     */
    public function newInstantiator(MetaDataInterface $metaData): InstantiatorResultInterface
    {
        $className = $metaData->getClassMetadata()->getClassName();
        $reflClass = new ReflectionClass($className);
        $processorFactory = $this->paramProcessorFactory;
        $excludedProperties = [];
        $paramProcessors = [];

        foreach ($metaData->getPropertyWithConstructorParamTuples() as $paramName => $tuple) {
            $excludedProperties[] = $paramName;
            $paramProcessors[] = $processorFactory->newParamProcessor($tuple);
        }

        return new InstantiatorResult(
            new StandardInstantiator($reflClass, $paramProcessors),
            new ExcludePropertiesMetaData($metaData, $excludedProperties)
        );
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
