<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\ClassModel;

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
        $annotations = [];

        $classMetaData = new ClassMetaData($className, $annotations);

        self::assertSame($className, $classMetaData->getClassName());
        self::assertSame($annotations, $classMetaData->getAnnotations());
    }
}
