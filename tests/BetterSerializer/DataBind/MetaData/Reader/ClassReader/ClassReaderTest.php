<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader\ClassReader;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaData;
use BetterSerializer\Reflection\ReflectionClassInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
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
        $annotationReaderStub = $this->getMockBuilder(AnnotationReader::class)
            ->disableOriginalConstructor()
            ->disableProxyingToOriginalMethods()
            ->getMock();
        $annotationReaderStub->expects(self::once())
            ->method('getClassAnnotations')
            ->willReturn([]);

        $nativeReflectionClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();

        $reflectionClassStub = $this->createMock(ReflectionClassInterface::class);
        $reflectionClassStub->expects(self::once())
            ->method('getNativeReflClass')
            ->willReturn($nativeReflectionClass);

        /* @var $annotationReaderStub AnnotationReader */
        $reader = new ClassReader($annotationReaderStub);
        $classMetaData = $reader->getClassMetadata($reflectionClassStub);

        self::assertInstanceOf(ClassMetaData::class, $classMetaData);
    }
}
