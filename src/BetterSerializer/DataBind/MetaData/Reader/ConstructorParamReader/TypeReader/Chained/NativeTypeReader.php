<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\Chained;

use BetterSerializer\DataBind\MetaData\Type\Factory\NativeTypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use ReflectionMethod;
use ReflectionParameter;

/**
 * Class NativeTypeReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Processor\TypeReader\Chained
 */
final class NativeTypeReader implements ChainedTypeReaderInterface
{

    /**
     * @var NativeTypeFactoryInterface
     */
    private $nativeTypeFactory;

    /**
     * @var string
     */
    private $currentNamespace;

    /**
     * NativeTypeReader constructor.
     * @param NativeTypeFactoryInterface $nativeTypeFactory
     */
    public function __construct(NativeTypeFactoryInterface $nativeTypeFactory)
    {
        $this->nativeTypeFactory = $nativeTypeFactory;
    }

    /**
     * @param ReflectionMethod $constructor
     */
    public function initialize(ReflectionMethod $constructor): void
    {
        $this->currentNamespace = $constructor->getNamespaceName();
    }

    /**
     * @param ReflectionParameter $parameter
     * @return TypeInterface
     */
    public function getType(ReflectionParameter $parameter): TypeInterface
    {
        $type = $parameter->getType();
        $stringFormType = new StringFormType((string) $type, $this->currentNamespace);

        return $this->nativeTypeFactory->getType($stringFormType);
    }
}
