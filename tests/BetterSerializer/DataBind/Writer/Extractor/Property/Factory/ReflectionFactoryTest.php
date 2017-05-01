<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Property\Factory;

use BetterSerializer\DataBind\MetaData\PropertyMetadataInterface;
use BetterSerializer\DataBind\Writer\Extractor\Property\ReflectionExtractor;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * Class ReflectionFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Extractor\Property\Factory
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ReflectionFactoryTest extends TestCase
{

    /**
     *
     */
    public function testNewExtractor(): void
    {
        /* @var $reflPropertyStub ReflectionProperty */
        $reflPropertyStub = Mockery::mock(ReflectionProperty::class);

        /* @var $propertyMetadataStub PropertyMetadataInterface */
        $propertyMetadataStub = Mockery::mock(
            PropertyMetadataInterface::class,
            ['getReflectionProperty' => $reflPropertyStub]
        );

        $factory = new ReflectionFactory();
        $extractor = $factory->newExtractor($propertyMetadataStub);

        self::assertInstanceOf(ReflectionExtractor::class, $extractor);
    }
}
