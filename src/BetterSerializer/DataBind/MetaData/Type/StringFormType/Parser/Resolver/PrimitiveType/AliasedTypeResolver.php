<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\PrimitiveType;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ResultInterface as FormatResultInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ContextInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ResolverInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ResultInterface;

/**
 *
 */
final class AliasedTypeResolver implements ResolverInterface
{

    /**
     * @var ResolverInterface
     */
    private $delegate;

    /**
     * @param ResolverInterface $delegate
     */
    public function __construct(ResolverInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * @param FormatResultInterface $formatResult
     * @param ContextInterface $context
     * @return ResultInterface|null
     */
    public function resolve(FormatResultInterface $formatResult, ContextInterface $context): ?ResultInterface
    {
        $aliasedFormatResult = new AliasedFormatResult($formatResult);

        return $this->delegate->resolve($aliasedFormatResult, $context);
    }
}
