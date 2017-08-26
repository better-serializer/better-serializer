<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Property;

use BetterSerializer\Dto\CarInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * Class ReflectionExtractorTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Extractor\Property
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
        $nativeReflProperty->expects(self::once())
            ->method('getValue')
            ->willReturn($value);

        $reflPropertyStub = $this->createMock(ReflectionPropertyInterface::class);
        $reflPropertyStub->method('getNativeReflProperty')
            ->willReturn($nativeReflProperty);

        $objectStub = $this->getMockBuilder(CarInterface::class)->getMock();

        $extractor = new ReflectionExtractor($reflPropertyStub);
        $extracted = $extractor->extract($objectStub);

        self::assertSame($value, $extracted);
    }
}
