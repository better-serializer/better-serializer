<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaData;
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
        $reflectionClassStub = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->disableProxyingToOriginalMethods()
            ->getMock();
        $reflectionClassStub->expects(self::once())
            ->method('getName')
            ->willReturn('test');

        /* @var $annotationReaderStub AnnotationReader */
        /* @var $reflectionClassStub ReflectionClass */
        $reader = new ClassReader($annotationReaderStub);
        $classMetaData = $reader->getClassMetadata($reflectionClassStub);

        self::assertInstanceOf(ClassMetaData::class, $classMetaData);
    }
}
