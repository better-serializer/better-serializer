<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection;

use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\CarInterface;
use BetterSerializer\Reflection\UseStatement\UseStatementsInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass as NativeReflectionClass;
use ReflectionExtension;
use ReflectionProperty as NativeReflectionProperty;
use ReflectionMethod;
use RuntimeException;

/**
 * Class ReflectionClassTest
 * @author mfris
 * @package BetterSerializer\Reflection
 */
class ReflectionClassTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testEverything(): void
    {
        $classProperty = $this->getMockBuilder(NativeReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $classProperty->method('getName')
            ->willReturn('testProperty');
        $name = 'Test';
        $isInternal = false;
        $isUserDefined = true;
        $isInstantiable = true;
        $isCloneable = false;
        $fileName = 'Test.php';
        $startLine = 15;
        $endLine = 45;
        $docDocComment = '/** asd */';
        $constructor = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hasMethod1 = 'method1';
        $hasMethod1Returns = true;
        $method1 = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();
        $method1->method('getName')
            ->willReturn($hasMethod1);
        $classPropertyName = 'testProperty';
        $hasClassProperty = true;
        $constName = 'testConstant';
        $constValue = 'constant';
        $hasConstName = true;
        $interface = $this->getMockBuilder(NativeReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $interfaceName = 'TestInterface';
        $isAnonymous = false;
        $isInterface = false;
        $trait = $this->getMockBuilder(NativeReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $traitName = 'TestTrait';
        $traitAlias = 'TraitAlias';
        $isTrait = false;
        $isAbstract = false;
        $isFinal = true;
        $modifiers = 3;
        $object = $this->createMock(CarInterface::class);
        $isInstance = true;
        $newInstance = $this->createMock(CarInterface::class);
        $subclassClass = Car::class;
        $isSubclass = true;
        $staticProperty = $this->getMockBuilder(NativeReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $staticPropertyName = 'staticPropertyName';
        $staticPropertyValue = 'staticPropertyValue';
        $newStaticPropValue = 'newStaticPropertyValue';
        $defStaticPropValue = 'default';
        $isIterateable = false;
        $implementsIfaceName = 'ImplementsInterface';
        $implementssInterface = false;
        $extension = $this->getMockBuilder(ReflectionExtension::class)
            ->disableOriginalConstructor()
            ->getMock();
        $extensionName = 'ExtensionName';
        $inNamespace = true;
        $namespace = 'TestNamespace';
        $shortName = 'Short';

        $nativeReflClass = $this->getMockBuilder(NativeReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $nativeReflClass->method('getProperties')
            ->willReturn([$classProperty]);
        $nativeReflClass->method('getName')
            ->willReturn($name);
        $nativeReflClass->method('isInternal')
            ->willReturn($isInternal);
        $nativeReflClass->method('isUserDefined')
            ->willReturn($isUserDefined);
        $nativeReflClass->method('isInstantiable')
            ->willReturn($isInstantiable);
        $nativeReflClass->method('isCloneable')
            ->willReturn($isCloneable);
        $nativeReflClass->method('getFileName')
            ->willReturn($fileName);
        $nativeReflClass->method('getStartLine')
            ->willReturn($startLine);
        $nativeReflClass->method('getEndLine')
            ->willReturn($endLine);
        $nativeReflClass->method('getDocComment')
            ->willReturn($docDocComment);
        $nativeReflClass->method('getConstructor')
            ->willReturn($constructor);
        $nativeReflClass->method('hasMethod')
            ->with($hasMethod1)
            ->willReturn($hasMethod1Returns);
        $nativeReflClass->method('getMethod')
            ->with($hasMethod1)
            ->willReturn($method1);
        $nativeReflClass->method('getMethods')
            ->willReturn([$method1]);
        $nativeReflClass->method('hasProperty')
            ->with($classPropertyName)
            ->willReturn($hasClassProperty);
        $nativeReflClass->method('getProperty')
            ->with($classPropertyName)
            ->willReturn($classProperty);
        $nativeReflClass->method('hasConstant')
            ->with($constName)
            ->willReturn($hasConstName);
        $nativeReflClass->method('getConstants')
            ->willReturn([$constName => $constValue]);
        $nativeReflClass->method('getConstant')
            ->with($constName)
            ->willReturn($constValue);
        $nativeReflClass->method('getInterfaces')
            ->willReturn([$interfaceName => $interface]);
        $nativeReflClass->method('getInterfaceNames')
            ->willReturn([$interfaceName]);
        $nativeReflClass->method('isAnonymous')
            ->willReturn($isAnonymous);
        $nativeReflClass->method('isInterface')
            ->willReturn($isInterface);
        $nativeReflClass->method('getTraits')
            ->willReturn([$traitName => $trait]);
        $nativeReflClass->method('getTraitNames')
            ->willReturn([$traitName]);
        $nativeReflClass->method('getTraitAliases')
            ->willReturn([$traitAlias => $traitName]);
        $nativeReflClass->method('isTrait')
            ->willReturn($isTrait);
        $nativeReflClass->method('isAbstract')
            ->willReturn($isAbstract);
        $nativeReflClass->method('isFinal')
            ->willReturn($isFinal);
        $nativeReflClass->method('getModifiers')
            ->willReturn($modifiers);
        $nativeReflClass->method('isInstance')
            ->with($object)
            ->willReturn($isInstance);
        $nativeReflClass->method('newInstance')
            ->willReturn($newInstance);
        $nativeReflClass->method('newInstanceWithoutConstructor')
            ->willReturn($newInstance);
        $nativeReflClass->method('newInstanceArgs')
            ->willReturn($newInstance);
        $nativeReflClass->method('isSubclassOf')
            ->with($subclassClass)
            ->willReturn($isSubclass);
        $nativeReflClass->method('getStaticProperties')
            ->willReturn([$staticProperty]);
        $nativeReflClass->method('getStaticPropertyValue')
            ->with($staticPropertyName)
            ->willReturn($staticPropertyValue);
        $nativeReflClass->expects(self::once())
            ->method('setStaticPropertyValue')
            ->with($staticPropertyName, $newStaticPropValue)
            ->willReturn($newInstance);
        $nativeReflClass->method('getDefaultProperties')
            ->willReturn([$staticPropertyName => $defStaticPropValue]);
        $nativeReflClass->method('isIterateable')
            ->willReturn($isIterateable);
        $nativeReflClass->method('implementsInterface')
            ->with($implementsIfaceName)
            ->willReturn($implementssInterface);
        $nativeReflClass->method('getExtension')
            ->willReturn($extension);
        $nativeReflClass->method('getExtensionName')
            ->willReturn($extensionName);
        $nativeReflClass->method('inNamespace')
            ->willReturn($inNamespace);
        $nativeReflClass->method('getNamespaceName')
            ->willReturn($namespace);
        $nativeReflClass->method('getShortName')
            ->willReturn($shortName);

        $useStatements = $this->createMock(UseStatementsInterface::class);

        $parentProperty = $this->getMockBuilder(NativeReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();

        $parentReflProperty = $this->createMock(ReflectionPropertyInterface::class);
        $parentReflProperty->method('getNativeReflProperty')
            ->willReturn($parentProperty);

        $parentClass = $this->createMock(ReflectionClassInterface::class);
        $parentClass->method('getProperties')
            ->willReturn([$parentReflProperty]);


        /* @var $nativeReflClass NativeReflectionClass */
        $reflClass = new ReflectionClass($nativeReflClass, $useStatements, $parentClass);

        self::assertSame($nativeReflClass, $reflClass->getNativeReflClass());
        self::assertSame($useStatements, $reflClass->getUseStatements());

        $properties = $reflClass->getProperties();
        self::assertCount(2, $properties);
        self::assertInstanceOf(ReflectionPropertyInterface::class, $properties[0]);
        self::assertSame($parentProperty, $properties[0]->getNativeReflProperty());
        self::assertInstanceOf(ReflectionPropertyInterface::class, $properties[1]);
        self::assertSame($classProperty, $properties[1]->getNativeReflProperty());
        self::assertSame($properties, $reflClass->getProperties());

        self::assertSame($parentClass, $reflClass->getParentClass());
        self::assertSame($name, $reflClass->getName());
        self::assertSame($isInternal, $reflClass->isInternal());
        self::assertSame($isUserDefined, $reflClass->isUserDefined());
        self::assertSame($isInstantiable, $reflClass->isInstantiable());
        self::assertSame($isCloneable, $reflClass->isCloneable());
        self::assertSame($fileName, $reflClass->getFileName());
        self::assertSame($startLine, $reflClass->getStartLine());
        self::assertSame($endLine, $reflClass->getEndLine());
        self::assertSame($docDocComment, $reflClass->getDocComment());
        self::assertSame($constructor, $reflClass->getConstructor()->getNativeReflMethod());
        self::assertSame($constructor, $reflClass->getConstructor()->getNativeReflMethod()); // lazy instantialization
        self::assertSame($hasMethod1Returns, $reflClass->hasMethod($hasMethod1));
        self::assertSame($method1, $reflClass->getMethod($hasMethod1)->getNativeReflMethod());
        $methods = $reflClass->getMethods();
        self::assertInternalType('array', $methods);
        self::assertCount(1, $methods);
        self::assertSame($method1, $methods[0]->getNativeReflMethod());
        self::assertSame($hasClassProperty, $reflClass->hasProperty($classPropertyName));
        self::assertSame($classProperty, $reflClass->getProperty($classPropertyName)->getNativeReflProperty());
        self::assertSame($hasConstName, $reflClass->hasConstant($constName));
        self::assertSame([$constName => $constValue], $reflClass->getConstants());
        self::assertSame($constValue, $reflClass->getConstant($constName));
        self::assertSame([$interfaceName => $interface], $reflClass->getInterfaces());
        self::assertSame([$interfaceName], $reflClass->getInterfaceNames());
        self::assertSame($isAnonymous, $reflClass->isAnonymous());
        self::assertSame($isInterface, $reflClass->isInterface());
        self::assertSame([$traitName => $trait], $reflClass->getTraits());
        self::assertSame([$traitName], $reflClass->getTraitNames());
        self::assertSame([$traitAlias => $traitName], $reflClass->getTraitAliases());
        self::assertSame($isTrait, $reflClass->isTrait());
        self::assertSame($isAbstract, $reflClass->isAbstract());
        self::assertSame($isFinal, $reflClass->isFinal());
        self::assertSame($modifiers, $reflClass->getModifiers());
        self::assertSame($isInstance, $reflClass->isInstance($object));
        self::assertSame($newInstance, $reflClass->newInstance());
        self::assertSame($newInstance, $reflClass->newInstanceWithoutConstructor());
        self::assertSame($newInstance, $reflClass->newInstanceArgs());
        self::assertSame($isSubclass, $reflClass->isSubclassOf($subclassClass));
        self::assertSame([$staticProperty], $reflClass->getStaticProperties());
        self::assertSame($staticPropertyValue, $reflClass->getStaticPropertyValue($staticPropertyName));
        $reflClass->setStaticPropertyValue($staticPropertyName, $newStaticPropValue);
        self::assertSame([$staticPropertyName => $defStaticPropValue], $reflClass->getDefaultProperties());
        self::assertSame($isIterateable, $reflClass->isIterateable());
        self::assertSame($implementssInterface, $reflClass->implementsInterface($implementsIfaceName));
        self::assertSame($extension, $reflClass->getExtension());
        self::assertSame($extensionName, $reflClass->getExtensionName());
        self::assertSame($inNamespace, $reflClass->inNamespace());
        self::assertSame($namespace, $reflClass->getNamespaceName());
        self::assertSame($shortName, $reflClass->getShortName());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Property '[a-zA-Z0-9_]+' doesn't exist in class '[a-zA-Z0-9_]+'\./
     */
    public function testGetPropertyThrows(): void
    {
        $nativeReflClass = $this->getMockBuilder(NativeReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $nativeReflClass->method('getProperties')
            ->willReturn([]);
        $nativeReflClass->method('getName')
            ->willReturn('TestClass');

        $useStatements = $this->createMock(UseStatementsInterface::class);

        /* @var $nativeReflClass NativeReflectionClass */
        $reflClass = new ReflectionClass($nativeReflClass, $useStatements);
        $reflClass->getProperty('test');
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Method '[a-zA-Z0-9_]+' doesn't exist in class '[a-zA-Z0-9_]+'\./
     */
    public function testGetMethodThrows(): void
    {
        $nativeReflClass = $this->getMockBuilder(NativeReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $nativeReflClass->method('getMethods')
            ->willReturn([]);
        $nativeReflClass->method('getName')
            ->willReturn('TestClass');

        $useStatements = $this->createMock(UseStatementsInterface::class);

        /* @var $nativeReflClass NativeReflectionClass */
        $reflClass = new ReflectionClass($nativeReflClass, $useStatements);
        $reflClass->getMethod('test');
    }

    /**
     *
     */
    public function testGetConstructorReturnsNull(): void
    {
        $nativeReflClass = $this->getMockBuilder(NativeReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $nativeReflClass->expects(self::once())
            ->method('getConstructor')
            ->willReturn(null);

        $useStatements = $this->createMock(UseStatementsInterface::class);

        /* @var $nativeReflClass NativeReflectionClass */
        $reflClass = new ReflectionClass($nativeReflClass, $useStatements);
        $constructor = $reflClass->getConstructor();

        self::assertNull($constructor);
    }

    /**
     *
     */
    public function testUnserialize(): void
    {
        $nativeReflClass = $this->getMockBuilder(NativeReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $nativeReflClass->expects(self::once())
            ->method('getName')
            ->willReturn(Car::class);

        $useStatements = $this->createMock(UseStatementsInterface::class);

        /* @var $nativeReflClass NativeReflectionClass */
        $reflClass = new ReflectionClass($nativeReflClass, $useStatements);

        $serialized = serialize($reflClass);
        unserialize($serialized);
    }
}
