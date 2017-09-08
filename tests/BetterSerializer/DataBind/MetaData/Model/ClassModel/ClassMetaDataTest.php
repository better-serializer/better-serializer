<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\ClassModel;

use BetterSerializer\Reflection\ReflectionClassInterface;
use PHPUnit\Framework\TestCase;

/**
 * ClassModel ClassMetadataTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 */
class ClassMetaDataTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $className = 'test';
        $reflectionClass = $this->createMock(ReflectionClassInterface::class);
        $reflectionClass->method('getName')
            ->willReturn($className);
        $annotations = [];

        $classMetaData = new ClassMetaData($reflectionClass, $annotations);

        self::assertSame($reflectionClass, $classMetaData->getReflectionClass());
        self::assertSame($className, $classMetaData->getClassName());
        self::assertSame($annotations, $classMetaData->getAnnotations());
    }
}
