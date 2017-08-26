<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader\ClassReader;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaData;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use BetterSerializer\Reflection\ReflectionClassInterface;

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
        $reflectionClassStub = $this->createMock(ReflectionClassInterface::class);
        $reflectionClassStub->expects(self::once())
            ->method('getName')
            ->willReturn('test');

        /* @var $annotationReaderStub AnnotationReader */
        $reader = new ClassReader($annotationReaderStub);
        $classMetaData = $reader->getClassMetadata($reflectionClassStub);

        self::assertInstanceOf(ClassMetaData::class, $classMetaData);
    }
}
