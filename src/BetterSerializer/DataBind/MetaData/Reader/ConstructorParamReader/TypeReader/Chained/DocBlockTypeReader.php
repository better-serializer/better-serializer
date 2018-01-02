<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\Chained;

use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\StringTypeParserInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\MetaData\Type\UnknownType;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use BetterSerializer\Reflection\ReflectionMethodInterface;
use BetterSerializer\Reflection\ReflectionParameterInterface;

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
     * @var StringTypeParserInterface
     */
    private $stringTypeParser;

    /**
     * @var array|null
     */
    private $params;

    /**
     * DocBlockPropertyTypeReader constructor.
     * @param TypeFactoryInterface $typeFactory
     * @param DocBlockFactoryInterface $docBlockFactory
     * @param StringTypeParserInterface $stringTypeParser
     */
    public function __construct(
        TypeFactoryInterface $typeFactory,
        DocBlockFactoryInterface $docBlockFactory,
        StringTypeParserInterface $stringTypeParser
    ) {
        $this->typeFactory = $typeFactory;
        $this->docBlockFactory = $docBlockFactory;
        $this->stringTypeParser = $stringTypeParser;
    }

    /**
     * @param ReflectionMethodInterface $constructor
     * @throws \InvalidArgumentException
     */
    public function initialize(ReflectionMethodInterface $constructor): void
    {
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
     * @param ReflectionParameterInterface $parameter
     * @return TypeInterface
     */
    public function getType(ReflectionParameterInterface $parameter): TypeInterface
    {
        $name = $parameter->getName();

        if (!isset($this->params[$name])) {
            return new UnknownType();
        }

        $stringType = $this->params[$name];
        $stringFormType = $this->stringTypeParser->parseWithParentContext($stringType, $parameter->getDeclaringClass());

        return $this->typeFactory->getType($stringFormType);
    }
}
