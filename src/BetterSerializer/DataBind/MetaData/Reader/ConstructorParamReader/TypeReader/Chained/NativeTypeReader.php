<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\Chained;

use BetterSerializer\DataBind\MetaData\Type\Factory\NativeTypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Reflection\ReflectionMethodInterface;
use BetterSerializer\Reflection\ReflectionParameterInterface;
use LogicException;

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
     * NativeTypeReader constructor.
     * @param NativeTypeFactoryInterface $nativeTypeFactory
     */
    public function __construct(NativeTypeFactoryInterface $nativeTypeFactory)
    {
        $this->nativeTypeFactory = $nativeTypeFactory;
    }

    /**
     * @param ReflectionMethodInterface $constructor
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function initialize(ReflectionMethodInterface $constructor): void
    {
    }

    /**
     * @param ReflectionParameterInterface $parameter
     * @return TypeInterface
     * @throws LogicException
     */
    public function getType(ReflectionParameterInterface $parameter): TypeInterface
    {
        $type = $parameter->getType();
        $stringFormType = new ContextStringFormType((string) $type, $parameter->getDeclaringClass());

        return $this->nativeTypeFactory->getType($stringFormType);
    }
}
