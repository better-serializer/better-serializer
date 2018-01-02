<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Detector;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ResultInterface as FormatResultInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ResultInterface as ResolverResultInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ResultTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $formatResult = $this->createMock(FormatResultInterface::class);
        $resolverResult = $this->createMock(ResolverResultInterface::class);

        $result = new Result($formatResult, $resolverResult);

        self::assertSame($formatResult, $result->getFormatResult());
        self::assertSame($resolverResult, $result->getResolverResult());
    }
}
