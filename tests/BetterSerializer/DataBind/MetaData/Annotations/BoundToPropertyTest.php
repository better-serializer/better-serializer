<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Annotations;

use PHPUnit\Framework\TestCase;

/**
 * Class BoundToPropertyTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Annotations
 */
class BoundToPropertyTest extends TestCase
{

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Property name missing.
     */
    public function testEmptyThrowsException(): void
    {
        new BoundToProperty([]);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Argument name missing.
     */
    public function testEmptyArgumentThrowsException(): void
    {
        new BoundToProperty([BoundToProperty::KEY_PROPERTY_NAME => 'test']);
    }

    /**
     *
     */
    public function testWithData(): void
    {
        $propName = 'test';
        $argumentName = 'test2';

        $b2p = new BoundToProperty([
            BoundToProperty::KEY_PROPERTY_NAME => $propName,
            BoundToProperty::KEY_ARGUMENT_NAME => $argumentName
        ]);

        self::assertSame($propName, $b2p->getPropertyName());
        self::assertSame($argumentName, $b2p->getArgumentName());
        self::assertSame(BoundToProperty::ANNOTATION_NAME, $b2p->getAnnotationName());
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Property name cannot be empty.
     */
    public function testWithEmptyName(): void
    {
        new BoundToProperty([BoundToProperty::KEY_PROPERTY_NAME => '']);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Argument name cannot be empty.
     */
    public function testWithEmptyType(): void
    {
        new BoundToProperty([
            BoundToProperty::KEY_PROPERTY_NAME => 'test',
            BoundToProperty::KEY_ARGUMENT_NAME => ''
        ]);
    }
}
