<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Extension\Registry\Registrator;

use BetterSerializer\Common\CollectionExtensionInterface;
use BetterSerializer\Common\TypeExtensionInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\Chain\ExtensibleChainMemberInterface as ExtensibleTypeFactory;
use BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain as ReaderTypeChain;
use BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain as WriterTypeChain;
use BetterSerializer\Helper\ExtensionMockFactory;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RegistratorTest extends TestCase
{

    /**
     * @dataProvider getIsSupportedDataProvider
     * @param string $extTypeInterface
     * @param string $className
     * @param bool $expectedResult
     * @throws RuntimeException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \ReflectionException
     */
    public function testCreate(string $extTypeInterface, string $className, bool $expectedResult): void
    {
        $expectation = $expectedResult ? 1 : 0;

        $typeFactory = $this->createMock(ExtensibleTypeFactory::class);
        $typeFactory->expects(self::exactly($expectation))
            ->method('addExtensionClass')
            ->with($className);
        $readerProcFactory = $this->createMock(ReaderTypeChain\ExtensibleChainMemberInterface::class);
        $readerProcFactory->expects(self::exactly($expectation))
            ->method('addExtensionClass')
            ->with($className);
        $writerProcFactory = $this->createMock(WriterTypeChain\ExtensibleChainMemberInterface::class);
        $writerProcFactory->expects(self::exactly($expectation))
            ->method('addExtensionClass')
            ->with($className);
        $reflClass = new ReflectionClass($className);

        $registrator = new Registrator(
            $extTypeInterface,
            $typeFactory,
            $readerProcFactory,
            $writerProcFactory
        );

        self::assertSame($expectedResult, $registrator->register($reflClass));
        self::assertSame($extTypeInterface, $registrator->getExtTypeInterface());
    }

    /**
     * @return array
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \ReflectionException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getIsSupportedDataProvider(): array
    {
        $class1 = get_class(ExtensionMockFactory::createTypeExcensionMock('CustomType'));
        $class2 = get_class(ExtensionMockFactory::createCollectionExtensionMock('CustomType', true));

        return [
            [TypeExtensionInterface::class, $class1, true],
            [CollectionExtensionInterface::class, $class2, true],
            [TypeExtensionInterface::class, $class2, false],
            [CollectionExtensionInterface::class, $class1, false],
        ];
    }
}
