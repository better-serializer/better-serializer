<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ResultInterface as FormatResultInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType\TypeClassResolverChainInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType\TypeGuesserInterface;

/**
 *
 */
final class HigherTypeResolver implements ResolverInterface
{

    /**
     * @var TypeGuesserInterface[]
     */
    private $typeGuessers;

    /**
     * @var TypeClassResolverChainInterface
     */
    private $typeClassResolver;

    /**
     * @param TypeGuesserInterface[] $typeGuessers
     * @param TypeClassResolverChainInterface $typeClassResolver
     */
    public function __construct(array $typeGuessers, TypeClassResolverChainInterface $typeClassResolver)
    {
        $this->typeGuessers = $typeGuessers;
        $this->typeClassResolver = $typeClassResolver;
    }

    /**
     * @param FormatResultInterface $formatResult
     * @param ContextInterface $context
     * @return ResultInterface|null
     */
    public function resolve(
        FormatResultInterface $formatResult,
        ContextInterface $context
    ): ?ResultInterface {
        foreach ($this->typeGuessers as $typeGuesser) {
            $potentialType = $typeGuesser->guess($formatResult->getType(), $context);
            $typeClass = $this->typeClassResolver->resolveTypeClass($potentialType);

            if ($typeClass) {
                return new Result($potentialType, $typeClass);
            }
        }

        return null;
    }
}
