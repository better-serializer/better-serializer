<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Property;

use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class PropertyExtractorTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testExtract(): void
    {
        $title = 'car title';
        $color = 'black';
        $car = new Car($title, $color);

        $extractor = new PropertyExtractor('title', get_class($car));
        $extracted = $extractor->extract($car);

        self::assertSame($title, $extracted);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testExtractReturnsNull(): void
    {
        $extractor = new PropertyExtractor('title', Car::class);
        $extracted = $extractor->extract(null);

        self::assertNull($extracted);
    }

    /**
     *
     */
    public function testDeserialize(): void
    {
        $title = 'car title';
        $color = 'black';
        $car = new Car($title, $color);

        $extractor = new PropertyExtractor('title', get_class($car));

        $serialized = serialize($extractor);
        $extractor = unserialize($serialized);
        $extracted = $extractor->extract($car);

        self::assertSame($title, $extracted);
    }
}
