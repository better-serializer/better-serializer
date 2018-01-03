<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ResultInterface as FormatResultInterface;

/**
 *
 */
interface ResolverChainInterface
{

    /**
     * @param FormatResultInterface $formatResult
     * @param ContextInterface $context
     * @return ResultInterface|null
     */
    public function resolve(
        FormatResultInterface $formatResult,
        ContextInterface $context
    ): ResultInterface;
}
