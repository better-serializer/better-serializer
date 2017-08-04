<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\Chained;

use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\MetaData\Type\UnknownType;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use ReflectionMethod;
use ReflectionParameter;

/**
 * Class DocBlockTypeReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Processor\TypeReader\Chained
 */
final class DocBlockTypeReader implements ChainedTypeReaderInterface
{

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * @var DocBlockFactoryInterface
     */
    private $docBlockFactory;

    /**
     * @var string
     */
    private $currentNamespace;

    /**
     * @var array|null
     */
    private $params;

    /**
     * DocBlockPropertyTypeReader constructor.
     * @param TypeFactoryInterface $typeFactory
     * @param DocBlockFactoryInterface $docBlockFactory
     */
    public function __construct(TypeFactoryInterface $typeFactory, DocBlockFactoryInterface $docBlockFactory)
    {
        $this->typeFactory = $typeFactory;
        $this->docBlockFactory = $docBlockFactory;
    }

    /**
     * @param ReflectionMethod $constructor
     */
    public function initialize(ReflectionMethod $constructor): void
    {
        $this->currentNamespace = $constructor->getNamespaceName();
        $docComment = trim($constructor->getDocComment());

        if (!$docComment) {
            $this->params = [];

            return;
        }

        $docBlock = $this->docBlockFactory->create($docComment);
        /** @var Param[] $paramTags */
        $paramTags = $docBlock->getTagsByName('param');
        $this->params = [];

        foreach ($paramTags as $param) {
            $type = $param->getType();

            if ($type) {
                $this->params[$param->getVariableName()] = (string) $type;
            }
        }
    }

    /**
     * @param ReflectionParameter $parameter
     * @return TypeInterface
     */
    public function getType(ReflectionParameter $parameter): TypeInterface
    {
        $name = $parameter->getName();

        if (!isset($this->params[$name])) {
            return new UnknownType();
        }

        $stringType = $this->params[$name];
        $stringFormType = new StringFormType($stringType, $this->currentNamespace);

        return $this->typeFactory->getType($stringFormType);
    }
}
