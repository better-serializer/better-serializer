<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Detector\DetectorInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\Context;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ContextInterface;
use BetterSerializer\Reflection\ReflectionClassInterface;

/**
 *
 */
final class StringTypeParser implements StringTypeParserInterface
{

    /**
     * @var DetectorInterface
     */
    private $typeDetector;

    /**
     * @var ParametersParserInterface
     */
    private $parametersParser;

    /**
     * @param DetectorInterface $typeDetector
     * @param ParametersParserInterface $parametersParser
     */
    public function __construct(
        DetectorInterface $typeDetector,
        ParametersParserInterface $parametersParser
    ) {
        $this->typeDetector = $typeDetector;
        $this->parametersParser = $parametersParser;
    }

    /**
     * @param string $stringType
     * @param ReflectionClassInterface $contextReflClass
     * @return ContextStringFormTypeInterface
     */
    public function parseWithParentContext(
        string $stringType,
        ReflectionClassInterface $contextReflClass
    ): ContextStringFormTypeInterface {
        $context = new Context($contextReflClass->getNamespaceName(), $contextReflClass->getUseStatements());

        return $this->parseWithContext($stringType, $context);
    }

    /**
     * @param string $fqStringType
     * @return ContextStringFormTypeInterface
     */
    public function parseSimple(string $fqStringType): ContextStringFormTypeInterface
    {
        return $this->parseWithContext($fqStringType, new Context());
    }

    /**
     * @param string $stringType
     * @param ContextInterface $context
     * @return ContextStringFormTypeInterface
     */
    private function parseWithContext(string $stringType, ContextInterface $context): ContextStringFormTypeInterface
    {
        $detectorResult = $this->typeDetector->detectType($stringType, $context);
        $formatResult = $detectorResult->getFormatResult();
        $typeResult = $detectorResult->getResolverResult();
        $parameters = null;
        $nestedValueType = null;
        $nestedKeyType = null;

        if ($formatResult->getParameters()) {
            $parameters = $this->parametersParser->parseParameters($formatResult->getParameters());
        }

        if ($formatResult->getNestedValueType()) {
            $nestedValueType = $this->parseWithContext($formatResult->getNestedValueType(), $context);
        }

        if ($formatResult->getNestedKeyType()) {
            $nestedKeyType = $this->parseWithContext($formatResult->getNestedKeyType(), $context);
        }

        return new ContextStringFormType(
            $typeResult->getTypeName(),
            $context->getNamespace(),
            $typeResult->getTypeClass(),
            $parameters,
            $nestedValueType,
            $nestedKeyType
        );
    }
}
