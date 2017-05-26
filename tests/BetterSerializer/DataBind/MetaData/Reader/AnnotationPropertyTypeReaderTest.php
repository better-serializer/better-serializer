<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use PHPUnit\Framework\TestCase;
use Mockery;
use RuntimeException;

/**
 * Class AnnotationPropertyTypeReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class AnnotationPropertyTypeReaderTest extends TestCase
{

    /**
     *
     */
    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Property annotation missing.
     */
    public function testGetTypeWithoutAnnotations(): void
    {
        /* @var $typeFactoryStub Mockery\MockInterface */
        $typeFactoryStub = Mockery::mock(TypeFactoryInterface::class);
        $typeFactoryStub->shouldReceive('getType')
            ->times(0)
            ->getMock();
        /* @var $typeFactoryStub TypeFactoryInterface */

        $reader = new AnnotationPropertyTypeReader($typeFactoryStub);
        $reader->getType([]);
    }

    /**
     *
     */
    public function testGetTypeWithAnnotations(): void
    {
        $propertyAnnotStub1 = Mockery::mock(PropertyInterface::class, ['getType' => 'string']);

        /* @var $typeFactoryStub Mockery\MockInterface */
        $typeFactoryStub = Mockery::mock(TypeFactoryInterface::class);
        $typeFactoryStub = $typeFactoryStub->shouldReceive('getType')
            ->once()
            ->andReturn(new StringType())
            ->getMock();
        /* @var $typeFactoryStub TypeFactoryInterface */

        $reader = new AnnotationPropertyTypeReader($typeFactoryStub);
        $type = $reader->getType([$propertyAnnotStub1]);

        self::assertInstanceOf(StringType::class, $type);
    }
}
