<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParametersInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnumInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ContextStringFormTypeTest extends TestCase
{
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testEverything(): void
    {
        $type = 'string';
        $namespace = 'test';
        $typeClass = $this->createMock(TypeClassEnumInterface::class);
        $parameters = $this->createMock(ParametersInterface::class);
        $valueType = $this->createMock(ContextStringFormTypeInterface::class);
        $keyType = $this->createMock(ContextStringFormTypeInterface::class);

        $stringFormType = new ContextStringFormType(
            $type,
            $namespace,
            $typeClass,
            $parameters,
            $valueType,
            $keyType
        );

        self::assertSame($type, $stringFormType->getStringType());
        self::assertSame($namespace, $stringFormType->getNamespace());
        self::assertSame($typeClass, $stringFormType->getTypeClass());
        self::assertSame($parameters, $stringFormType->getParameters());
        self::assertSame($valueType, $stringFormType->getCollectionValueType());
        self::assertSame($keyType, $stringFormType->getCollectionKeyType());
    }
}
