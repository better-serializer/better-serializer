<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType;

use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\Dto\CarInterface;
use BetterSerializer\Extension\Registry\CollectionInterface;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ExtensionResolverTest extends TestCase
{

    /**
     * @param string $typeString
     * @param TypeClassEnum|null $expectedTypeClass
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @dataProvider resolveDataProvider
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testResolve(string $typeString, ?TypeClassEnum $expectedTypeClass): void
    {
        $extensions = $this->createMock(CollectionInterface::class);
        $extensions->expects(self::once())
            ->method('hasType')
            ->with($typeString)
            ->willReturn($expectedTypeClass === TypeClassEnum::EXTENSION_TYPE());

        $resolver = new ExtensionResolver($extensions);
        $typeClass = $resolver->resolveTypeClass($typeString);

        self::assertSame($expectedTypeClass, $typeClass);
    }

    /**
     * @return array
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function resolveDataProvider(): array
    {
        return [
            ['BooleanString', TypeClassEnum::EXTENSION_TYPE()],
            [Collection::class, TypeClassEnum::EXTENSION_TYPE()],
            [CarInterface::class, null],
        ];
    }
}
