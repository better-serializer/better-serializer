<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Property;

use BetterSerializer\Dto\CarInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 *
 */
class ReflectionExtractorTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testExtract(): void
    {
        $value = 5;

        $nativeReflProperty = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $nativeReflProperty->method('getValue')
            ->willReturn($value);

        $reflPropertyStub = $this->createMock(ReflectionPropertyInterface::class);
        $reflPropertyStub->method('getNativeReflProperty')
            ->willReturn($nativeReflProperty);

        $objectStub = $this->createMock(CarInterface::class);

        $extractor = new ReflectionExtractor($reflPropertyStub);
        $extracted = $extractor->extract($objectStub);

        self::assertSame($value, $extracted);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testExtractReturnsNull(): void
    {
        $reflPropertyStub = $this->createMock(ReflectionPropertyInterface::class);
        $extractor = new ReflectionExtractor($reflPropertyStub);
        $extracted = $extractor->extract(null);

        self::assertNull($extracted);
    }

    /**
     *
     */
    public function testDeserialize(): void
    {
        $nativeReflProperty = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();

        $reflPropertyStub = $this->createMock(ReflectionPropertyInterface::class);
        $reflPropertyStub->expects(self::atLeast(1))
            ->method('getNativeReflProperty')
            ->willReturn($nativeReflProperty);

        $extractor = new ReflectionExtractor($reflPropertyStub);

        $serialized = serialize($extractor);
        unserialize($serialized);
    }
}
