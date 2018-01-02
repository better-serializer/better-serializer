<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Detector;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ParserChainInterface as FormatParserInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ContextInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ResolverChainInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use LogicException;

/**
 *
 */
final class Detector implements DetectorInterface
{

    /**
     * @var FormatParserInterface
     */
    private $formatParser;

    /**
     * @var ResolverChainInterface
     */
    private $typeResolver;

    /**
     * @param FormatParserInterface $formatParser
     * @param ResolverChainInterface $typeResolver
     */
    public function __construct(FormatParserInterface $formatParser, ResolverChainInterface $typeResolver)
    {
        $this->formatParser = $formatParser;
        $this->typeResolver = $typeResolver;
    }

    /**
     * @param string $typeString
     * @param ContextInterface $context
     * @return ResultInterface
     * @throws LogicException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function detectType(string $typeString, ContextInterface $context): ResultInterface
    {
        $possibleTypeStrings = explode('|', $typeString);

        foreach ($possibleTypeStrings as $possibleTypeString) {
            $possibleTypeString = trim($possibleTypeString);
            $formatResult = $this->formatParser->parse($possibleTypeString);
            $typeResult = $this->typeResolver->resolve($formatResult, $context);

            if ($typeResult->getTypeClass() !== TypeClassEnum::UNKNOWN_TYPE()) {
                return new Result($formatResult, $typeResult);
            }
        }

        throw new LogicException('Invalid type: ' . $typeString);
    }
}
