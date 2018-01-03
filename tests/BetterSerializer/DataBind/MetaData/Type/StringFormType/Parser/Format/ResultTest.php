<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format;

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
        $type = 'Collection';
        $parameters = 'test=6';
        $nestedValueType = 'string';
        $nestedKeyType = 'int';

        $result = new Result($type, $parameters, $nestedValueType, $nestedKeyType);

        self::assertSame($type, $result->getType());
        self::assertSame($parameters, $result->getParameters());
        self::assertSame($nestedValueType, $result->getNestedValueType());
        self::assertSame($nestedKeyType, $result->getNestedKeyType());
    }
}
