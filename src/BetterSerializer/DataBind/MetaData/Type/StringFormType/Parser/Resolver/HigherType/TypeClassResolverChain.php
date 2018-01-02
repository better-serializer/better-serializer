<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType;

use BetterSerializer\DataBind\MetaData\Type\TypeClassEnumInterface;

/**
 *
 */
final class TypeClassResolverChain implements TypeClassResolverChainInterface
{

    /**
     * @var TypeClassResolverInterface[]
     */
    private $resolvers;

    /**
     * @param TypeClassResolverInterface[] $resolvers
     */
    public function __construct(array $resolvers)
    {
        $this->resolvers = $resolvers;
    }

    /**
     * @param string $potentialHigherType
     * @return TypeClassEnumInterface|null
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function resolveTypeClass(string $potentialHigherType): ?TypeClassEnumInterface
    {
        foreach ($this->resolvers as $resolver) {
            $typeClass = $resolver->resolveTypeClass($potentialHigherType);

            if ($typeClass) {
                return $typeClass;
            }
        }

        return null;
    }
}
