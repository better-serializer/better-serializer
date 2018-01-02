<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ResultInterface as FormatResultInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;

/**
 *
 */
final class ResolverChain implements ResolverChainInterface
{

    /**
     * @var ResolverInterface[]
     */
    private $resolvers;

    /**
     * @param ResolverInterface[] $resolvers
     */
    public function __construct(array $resolvers)
    {
        $this->resolvers = $resolvers;
    }

    /**
     * @param FormatResultInterface $formatResult
     * @param ContextInterface $context
     * @return ResultInterface
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function resolve(
        FormatResultInterface $formatResult,
        ContextInterface $context
    ): ResultInterface {
        foreach ($this->resolvers as $resolver) {
            $result = $resolver->resolve($formatResult, $context);

            if ($result) {
                return $result;
            }
        }

        return new Result($formatResult->getType(), TypeClassEnum::UNKNOWN_TYPE());
    }
}
