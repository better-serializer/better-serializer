<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeResolver;

use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\PropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use RuntimeException;

/**
 *
 */
final class TypeResolverChain implements TypeResolverChainInterface
{

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * @var TypeResolverInterface[]
     */
    private $typeResolvers;

    /**
     * @param TypeFactoryInterface $typeFactory
     * @param TypeResolverInterface[] $typeResolvers
     * @throws RuntimeException
     */
    public function __construct(TypeFactoryInterface $typeFactory, array $typeResolvers)
    {
        $this->typeFactory = $typeFactory;

        if (empty($typeResolvers)) {
            throw new RuntimeException('Type readers missing.');
        }

        $this->typeResolvers = $typeResolvers;
    }

    /**
     * @param PropertyContextInterface $context
     * @return TypeInterface
     * @throws RuntimeException
     */
    public function resolveType(PropertyContextInterface $context): TypeInterface
    {
        foreach ($this->typeResolvers as $typeReader) {
            $typedContext = $typeReader->resolveType($context);

            if ($typedContext) {
                return $this->typeFactory->getType($typedContext);
            }
        }

        $reflectionProperty = $context->getReflectionProperty();

        throw new RuntimeException(
            sprintf(
                'Type declaration missing in class: %s, property: %s.',
                $reflectionProperty->getDeclaringClass()->getName(),
                $reflectionProperty->getName()
            )
        );
    }
}
