<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Detector;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ResultInterface as FormatResultInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ResultInterface as ResolverResultInterface;

/**
 *
 */
final class Result implements ResultInterface
{

    /**
     * @var FormatResultInterface
     */
    private $formatResult;

    /**
     * @var ResolverResultInterface
     */
    private $resolverResult;

    /**
     * @param FormatResultInterface $formatResult
     * @param ResolverResultInterface $resolverResult
     */
    public function __construct(FormatResultInterface $formatResult, ResolverResultInterface $resolverResult)
    {
        $this->formatResult = $formatResult;
        $this->resolverResult = $resolverResult;
    }

    /**
     * @return FormatResultInterface
     */
    public function getFormatResult(): FormatResultInterface
    {
        return $this->formatResult;
    }

    /**
     * @return ResolverResultInterface
     */
    public function getResolverResult(): ResolverResultInterface
    {
        return $this->resolverResult;
    }
}
