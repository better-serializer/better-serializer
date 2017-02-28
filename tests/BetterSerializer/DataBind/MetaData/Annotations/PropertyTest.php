<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Annotations;

use PHPUnit\Framework\TestCase;

/**
 * Class PropertyTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Annotations
 */
class PropertyTest extends TestCase
{

    /**
     *
     */
    public function testEmpty(): void
    {
        $property = new Property([]);

        self::assertSame('', $property->getName());
        self::assertSame('', $property->getType());
    }

    /**
     *
     */
    public function testWithData(): void
    {
        $property = new Property([Property::KEY_NAME => 'test', Property::KEY_TYPE => 'string']);

        self::assertSame('test', $property->getName());
        self::assertSame('string', $property->getType());
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Name property cannot be empty if set.
     */
    public function testWithEmptyName(): void
    {
        new Property([Property::KEY_NAME => '']);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Type property cannot be empty if set.
     */
    public function testWithEmptyType(): void
    {
        new Property([Property::KEY_TYPE => '']);
    }
}
