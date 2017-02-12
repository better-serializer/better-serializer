<?php
/**
 * @author  mfris
 */
declare(strict_types = 1);

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\ClassMetadata;
use BetterSerializer\Dto\Car;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ClassReaderTest extends TestCase
{

    /**
     *
     */
    public function testGetClassMetadata(): void
    {
        $annotationReader = $this->getMockBuilder(AnnotationReader::class)->getMock();
        $annotationReader->method('getClassAnnotations')->willReturn([]);

        /* @var $annotationReader AnnotationReader */
        $reader = new ClassReader($annotationReader);
        $reflectionClass = new ReflectionClass(Car::class);
        $classMetaData = $reader->getClassMetadata($reflectionClass);

        self::assertInstanceOf(ClassMetadata::class, $classMetaData);
    }
}
