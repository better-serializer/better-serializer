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
interface ResultInterface
{

    /**
     * @return FormatResultInterface
     */
    public function getFormatResult(): FormatResultInterface;

    /**
     * @return ResolverResultInterface
     */
    public function getResolverResult(): ResolverResultInterface;
}
