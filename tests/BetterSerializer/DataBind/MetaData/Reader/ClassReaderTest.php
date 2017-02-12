<?php
/**
 * @author  mfris
 */
declare(strict_types = 1);

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\ClassMetadata;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Mockery;
use ReflectionClass;

/**
 * Class ClassReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ClassReaderTest extends TestCase
{

    /**
     *
     */
    public function testGetClassMetadata(): void
    {
        $annotationReaderStub = Mockery::mock(AnnotationReader::class, ['getClassAnnotations' => []]);
        /* @var $annotationReaderStub AnnotationReader */
        $reader = new ClassReader($annotationReaderStub);
        $reflectionClassStub = Mockery::mock(ReflectionClass::class, ['getProperties' => []]);
        /* @var $reflectionClassStub ReflectionClass */
        $classMetaData = $reader->getClassMetadata($reflectionClassStub);

        self::assertInstanceOf(ClassMetadata::class, $classMetaData);
    }
}
