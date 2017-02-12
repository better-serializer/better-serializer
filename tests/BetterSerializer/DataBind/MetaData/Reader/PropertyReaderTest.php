<?php
/**
 * @author  mfris
 */
declare(strict_types = 1);

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\Dto\Car;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class PropertyReaderTest extends TestCase
{

    /**
     *
     */
    public function testGetPropertyMetadata(): void
    {
        $annotationReaderStub = $this->getMockBuilder(AnnotationReader::class)->getMock();
        $annotationReaderStub->method('getPropertyAnnotations')->willReturn([]);

        /* @var $annotationReaderStub AnnotationReader */
        $reader = new PropertyReader($annotationReaderStub);
        $reflectionClass = new ReflectionClass(Car::class);
        $propertyMetadata = $reader->getPropertyMetadata($reflectionClass);

        self::assertInternalType('array', $propertyMetadata);
    }
}
