<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Annotations;

use PHPUnit\Framework\TestCase;

/**
 * Class RootNameTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Annotations
 */
class RootNameTest extends TestCase
{

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Value missing.
     */
    public function testEmpty(): void
    {
        new RootName([]);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Value property cannot be empty if set.
     */
    public function testEmptyString(): void
    {
        new RootName([RootName::KEY_VALUE => '']);
    }

    /**
     *
     */
    public function testWithData(): void
    {
        $rootName = new RootName([RootName::KEY_VALUE => 'root']);

        self::assertSame('root', $rootName->getValue());
        self::assertSame(RootName::ANNOTATION_NAME, $rootName->getAnnotationName());
    }
}
