<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ContextInterface;

/**
 *
 */
final class UseStmtGuesser implements TypeGuesserInterface
{

    /**
     * @var NamespaceFragmentsParserInterface
     */
    private $nsFragmentsParser;

    /**
     * @param NamespaceFragmentsParserInterface $nsFragmentsParser
     */
    public function __construct(NamespaceFragmentsParserInterface $nsFragmentsParser)
    {
        $this->nsFragmentsParser = $nsFragmentsParser;
    }

    /**
     * @param string $potentialHigherType
     * @param ContextInterface $context
     * @return string
     * @throws \LogicException
     */
    public function guess(string $potentialHigherType, ContextInterface $context): string
    {
        $potentialHigherType = ltrim($potentialHigherType, '\\');
        $fragments = $this->nsFragmentsParser->parse($potentialHigherType);
        $firstFragment = $fragments->getFirst();

        $expectedClass = $context->getNamespace() . '\\' . $potentialHigherType;
        $useStatements = $context->getUseStatements();

        if ($firstFragment === '') {
            return rtrim($expectedClass, '\\');
        }

        if ($useStatements->hasByIdentifier($firstFragment)) {
            $useStatement = $useStatements->getByIdentifier($firstFragment);
            $expectedClass = $useStatement->getFqdn() . '\\' . $fragments->getWithoutFirst();
        } elseif ($useStatements->hasByAlias($firstFragment)) {
            $useStatement = $useStatements->getByAlias($firstFragment);
            $expectedClass = $useStatement->getFqdn() . '\\' . $fragments->getWithoutFirst();
        }

        return rtrim($expectedClass, '\\');
    }
}
