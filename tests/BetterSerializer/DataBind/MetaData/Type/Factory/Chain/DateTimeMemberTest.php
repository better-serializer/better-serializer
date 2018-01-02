<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\DateTimeType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParameterInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParametersInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use PHPUnit\Framework\TestCase;
use DateTime;
use DateTimeImmutable;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class DateTimeMemberTest extends TestCase
{

    /**
     * @dataProvider classNameProvider
     * @param string $stringType
     * @param string|null $format
     * @param bool $hasParameters
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @SuppressWarnings(PHPMD.ElseExpression)
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetType(string $stringType, ?string $format, bool $hasParameters = true): void
    {
        $stringFormType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringFormType->expects(self::exactly(2))
            ->method('getStringType')
            ->willReturn($stringType);
        $stringFormType->expects(self::any())
            ->method('getTypeClass')
            ->willReturn(TypeClassEnum::CLASS_TYPE());

        $parameters = null;

        if ($format && $hasParameters) {
            $parameters = $this->createMock(ParametersInterface::class);
            $parameters->expects(self::once())
                ->method('has')
                ->with('format')
                ->willReturn($format !== null);

            if ($format) {
                $parameter = $this->createMock(ParameterInterface::class);
                $parameter->expects(self::once())
                    ->method('getValue')
                    ->willReturn($format);

                $parameters->expects(self::once())
                    ->method('get')
                    ->with('format')
                    ->willReturn($parameter);
            }
        }

        $stringFormType->expects(self::once())
            ->method('getParameters')
            ->willReturn($parameters);

        $dateTimeMember = new DateTimeMember();
        /* @var $dateTimeType DateTimeType */
        $dateTimeType = $dateTimeMember->getType($stringFormType);

        self::assertNotNull($dateTimeType);
        self::assertInstanceOf(DateTimeType::class, $dateTimeType);
        self::assertSame($stringType, $dateTimeType->getClassName());

        if ($format) {
            self::assertSame($format, $dateTimeType->getFormat());
        } else {
            self::assertSame(DateTime::ATOM, $dateTimeType->getFormat());
        }
    }

    /**
     * @return array
     */
    public function classNameProvider(): array
    {
        return [
            [DateTime::class, 'Y-m-d'],
            [DateTime::class, DateTime::ATOM],
            [DateTime::class, null, false],
            [DateTimeImmutable::class, 'Y-m-d'],
            [DateTimeImmutable::class, DateTime::ATOM],
            [DateTimeImmutable::class, null, false],
        ];
    }

    /**
     * @dataProvider wrongClassNameProvider
     * @param string $stringType
     * @param TypeClassEnum $typeClass
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetTypeReturnsNull(string $stringType, TypeClassEnum $typeClass): void
    {
        $stringFormType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringFormType->expects(self::exactly($typeClass === TypeClassEnum::CLASS_TYPE() ? 1 : 0))
            ->method('getStringType')
            ->willReturn($stringType);
        $stringFormType->expects(self::any())
            ->method('getTypeClass')
            ->willReturn($typeClass);

        $dateTimeMember = new DateTimeMember();
        $type = $dateTimeMember->getType($stringFormType);

        self::assertNull($type);
    }

    /**
     * @return array
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function wrongClassNameProvider(): array
    {
        return [
            ['DateTimer', TypeClassEnum::CLASS_TYPE()],
            ['abc', TypeClassEnum::UNKNOWN_TYPE()],
            ['BetterSerializer\\Dto\\DateTime', TypeClassEnum::CLASS_TYPE()]
        ];
    }
}
