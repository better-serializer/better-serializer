<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Property;

use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;
use Mockery;
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

        /* @var $reflPropertyStub ReflectionProperty */
        $reflPropertyStub = Mockery::mock(ReflectionProperty::class, ['getValue' => $value]);
        $objectStub = Mockery::mock(CarInterface::class);

        $extractor = new ReflectionExtractor($reflPropertyStub);
        $extracted = $extractor->extract($objectStub);

        self::assertSame($value, $extracted);
    }
}
