<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\Chained;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use ReflectionMethod;
use ReflectionParameter;
use RuntimeException;

/**
 * Class TypeReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Processor\TypeReader
 */
final class TypeReader implements TypeReaderInterface
{

    /**
     * @var Chained\ChainedTypeReaderInterface[]
     */
    private $typeReaders;

    /**
     * TypeReader constructor.
     * @param Chained\ChainedTypeReaderInterface[] $typeReaders
     * @throws RuntimeException
     */
    public function __construct(array $typeReaders)
    {
        if (empty($typeReaders)) {
            throw new RuntimeException('Chained type readers missing.');
        }

        $this->typeReaders = $typeReaders;
    }

    /**
     * @param ReflectionMethod $constructor
     * @return TypeInterface[]
     */
    public function getParameterTypes(ReflectionMethod $constructor): array
    {
        $this->initializeTypeReaders($constructor);
        $parameterTypes = [];

        foreach ($constructor->getParameters() as $parameter) {
            $parameterTypes[$parameter->getName()] = $this->retrieveParameterType($parameter);
        }

        return $parameterTypes;
    }

    /**
     * @param ReflectionMethod $constructor
     */
    private function initializeTypeReaders(ReflectionMethod $constructor): void
    {
        foreach ($this->typeReaders as $typeReader) {
            $typeReader->initialize($constructor);
        }
    }

    /**
     * @param ReflectionParameter $parameter
     * @return TypeInterface
     */
    private function retrieveParameterType(ReflectionParameter $parameter): TypeInterface
    {
        /* @var $type TypeInterface */
        $type = null;

        foreach ($this->typeReaders as $typeReader) {
            $newType = $typeReader->getType($parameter);

            if (!$type || $type->isCompatibleWith($newType)) {
                $type = $newType;
            }
        }

        return $type;
    }
}
