<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\DateTimeType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use DateTime;
use DateTimeImmutable;
use LogicException;

/**
 * Class DateTimeMember
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Converter\Chain
 */
final class DateTimeMember extends ChainMember
{

    /**
     * @var string[]
     */
    private static $allowedTypes = [
        DateTime::class => DateTime::class,
        DateTimeImmutable::class => DateTimeImmutable::class,
    ];

    /**
     * @param ContextStringFormTypeInterface $stringFormType
     * @return bool
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function isProcessable(ContextStringFormTypeInterface $stringFormType): bool
    {
        return $stringFormType->getTypeClass() === TypeClassEnum::CLASS_TYPE() &&
            isset(self::$allowedTypes[$stringFormType->getStringType()]);
    }

    /**
     * @param ContextStringFormTypeInterface $stringFormType
     * @return TypeInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @throws LogicException
     */
    protected function createType(ContextStringFormTypeInterface $stringFormType): TypeInterface
    {
        $format = null;
        $parameters = $stringFormType->getParameters();

        if ($parameters && $parameters->has('format')) {
            $formatParam = $parameters->get('format');
            $format = trim((string) $formatParam->getValue());
        }

        if ($format) {
            return new DateTimeType($stringFormType->getStringType(), $format);
        }

        return new DateTimeType($stringFormType->getStringType());
    }
}
