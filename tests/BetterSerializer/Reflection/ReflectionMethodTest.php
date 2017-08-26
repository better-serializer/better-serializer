<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection;

use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;
use ReflectionMethod as NativeReflectionMethod;
use ReflectionExtension;
use ReflectionParameter;
use ReflectionType;

/**
 * Class ReflectionMethodTest
 * @author mfris
 * @package BetterSerializer\Reflection
 */
class ReflectionMethodTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testEverything(): void
    {
        $isPublic = true;
        $isPrivate = false;
        $isProtected = false;
        $isAbstract = false;
        $isFinal = false;
        $isStatic = false;
        $isConstructor = false;
        $isDestructor = false;
        $object = $this->createMock(CarInterface::class);
        $closure = function () {
            return 1;
        };
        $modifiers = 3;
        $invokeParameter = 1;
        $optional = 2;
        $args = [];
        $accessible = true;
        $inNamespace = true;
        $isClosure = false;
        $isDeprecated = false;
        $isInternal = false;
        $isUserDefined = true;
        $docComment = '/** asd */';
        $endLine = 28;
        $extension = $this->getMockBuilder(ReflectionExtension::class)
            ->disableOriginalConstructor()
            ->getMock();
        $extensionName = 'extensionName';
        $fileName = 'file.php';
        $name = 'ClassName';
        $namespace = 'NamespaceName';
        $paramsNumber = 1;
        $requiredParamsNumber = 1;
        $parameter = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $type = $this->getMockBuilder(ReflectionType::class)
            ->disableOriginalConstructor()
            ->getMock();
        $shortName = 'short';
        $startLine = 15;
        $statics = [];
        $hasReturnType = true;
        $returnsReference = false;
        $isGenerator = false;
        $isVariadic = false;

        $nativeReflMethod = $this->getMockBuilder(NativeReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();
        $nativeReflMethod->method('isPublic')
            ->willReturn($isPublic);
        $nativeReflMethod->method('isPrivate')
            ->willReturn($isPrivate);
        $nativeReflMethod->method('isProtected')
            ->willReturn($isProtected);
        $nativeReflMethod->method('isAbstract')
            ->willReturn($isAbstract);
        $nativeReflMethod->method('isFinal')
            ->willReturn($isFinal);
        $nativeReflMethod->method('isStatic')
            ->willReturn($isStatic);
        $nativeReflMethod->method('isConstructor')
            ->willReturn($isConstructor);
        $nativeReflMethod->method('isDestructor')
            ->willReturn($isDestructor);
        $nativeReflMethod->method('getClosure')
            ->with($object)
            ->willReturn($closure);
        $nativeReflMethod->method('getModifiers')
            ->willReturn($modifiers);
        $nativeReflMethod->expects(self::once())
            ->method('invoke')
            ->with($object, $invokeParameter, $optional)
            ->willReturn(null);
        $nativeReflMethod->expects(self::once())
            ->method('invokeArgs')
            ->with($object, $args)
            ->willReturn(null);
        $nativeReflMethod->method('getPrototype')
            ->willReturn($nativeReflMethod);
        $nativeReflMethod->expects(self::once())
            ->method('setAccessible')
            ->with($accessible);
        $nativeReflMethod->method('inNamespace')
            ->willReturn($inNamespace);
        $nativeReflMethod->method('isClosure')
            ->willReturn($isClosure);
        $nativeReflMethod->method('isDeprecated')
            ->willReturn($isDeprecated);
        $nativeReflMethod->method('isInternal')
            ->willReturn($isInternal);
        $nativeReflMethod->method('isUserDefined')
            ->willReturn($isUserDefined);
        $nativeReflMethod->method('getDocComment')
            ->willReturn($docComment);
        $nativeReflMethod->method('getEndLine')
            ->willReturn($endLine);
        $nativeReflMethod->method('getExtension')
            ->willReturn($extension);
        $nativeReflMethod->method('getExtensionName')
            ->willReturn($extensionName);
        $nativeReflMethod->method('getFileName')
            ->willReturn($fileName);
        $nativeReflMethod->method('getName')
            ->willReturn($name);
        $nativeReflMethod->method('getNamespaceName')
            ->willReturn($namespace);
        $nativeReflMethod->method('getNumberOfParameters')
            ->willReturn($paramsNumber);
        $nativeReflMethod->method('getNumberOfRequiredParameters')
            ->willReturn($requiredParamsNumber);
        $nativeReflMethod->method('getParameters')
            ->willReturn([$parameter]);
        $nativeReflMethod->method('getReturnType')
            ->willReturn($type);
        $nativeReflMethod->method('getShortName')
            ->willReturn($shortName);
        $nativeReflMethod->method('getStartLine')
            ->willReturn($startLine);
        $nativeReflMethod->method('getStaticVariables')
            ->willReturn($statics);
        $nativeReflMethod->method('hasReturnType')
            ->willReturn($hasReturnType);
        $nativeReflMethod->method('returnsReference')
            ->willReturn($returnsReference);
        $nativeReflMethod->method('isGenerator')
            ->willReturn($isGenerator);
        $nativeReflMethod->method('isVariadic')
            ->willReturn($isVariadic);

        $declaringClass = $this->createMock(ReflectionClassInterface::class);

        /** @var $nativeReflMethod NativeReflectionMethod */
        $reflMethod = new ReflectionMethod($nativeReflMethod, $declaringClass);

        self::assertSame($nativeReflMethod, $reflMethod->getNativeReflMethod());
        self::assertSame($isPublic, $reflMethod->isPublic());
        self::assertSame($isPrivate, $reflMethod->isPrivate());
        self::assertSame($isProtected, $reflMethod->isProtected());
        self::assertSame($isAbstract, $reflMethod->isAbstract());
        self::assertSame($isFinal, $reflMethod->isFinal());
        self::assertSame($isStatic, $reflMethod->isStatic());
        self::assertSame($isConstructor, $reflMethod->isConstructor());
        self::assertSame($isDestructor, $reflMethod->isDestructor());
        self::assertSame($closure, $reflMethod->getClosure($object));
        self::assertSame($modifiers, $reflMethod->getModifiers());
        self::assertNull($reflMethod->invoke($object, $invokeParameter, $optional));
        self::assertNull($reflMethod->invokeArgs($object, $args));
        self::assertSame($declaringClass, $reflMethod->getDeclaringClass());
        self::assertSame($nativeReflMethod, $reflMethod->getPrototype());
        $reflMethod->setAccessible($accessible);
        self::assertSame($inNamespace, $reflMethod->inNamespace());
        self::assertSame($isClosure, $reflMethod->isClosure());
        self::assertSame($isDeprecated, $reflMethod->isDeprecated());
        self::assertSame($isInternal, $reflMethod->isInternal());
        self::assertSame($isUserDefined, $reflMethod->isUserDefined());
        self::assertSame($docComment, $reflMethod->getDocComment());
        self::assertSame($endLine, $reflMethod->getEndLine());
        self::assertSame($extension, $reflMethod->getExtension());
        self::assertSame($extensionName, $reflMethod->getExtensionName());
        self::assertSame($fileName, $reflMethod->getFileName());
        self::assertSame($name, $reflMethod->getName());
        self::assertSame($namespace, $reflMethod->getNamespaceName());
        self::assertSame($paramsNumber, $reflMethod->getNumberOfParameters());
        self::assertSame($requiredParamsNumber, $reflMethod->getNumberOfRequiredParameters());
        $parameters = $reflMethod->getParameters();
        self::assertInternalType('array', $parameters);
        self::assertCount(1, $parameters);
        $parameters2 = $reflMethod->getParameters();
        self::assertSame($parameters, $parameters2);
        self::assertSame($parameter, $parameters[0]->getNativeReflParameter());
        self::assertSame($type, $reflMethod->getReturnType());
        self::assertSame($shortName, $reflMethod->getShortName());
        self::assertSame($startLine, $reflMethod->getStartLine());
        self::assertSame($statics, $reflMethod->getStaticVariables());
        self::assertSame($hasReturnType, $reflMethod->hasReturnType());
        self::assertSame($returnsReference, $reflMethod->returnsReference());
        self::assertSame($isGenerator, $reflMethod->isGenerator());
        self::assertSame($isVariadic, $reflMethod->isVariadic());
    }
}
