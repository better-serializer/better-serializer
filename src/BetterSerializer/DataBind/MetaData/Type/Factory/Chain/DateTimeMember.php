<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\DateTimeType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use LogicException;

/**
 * Class DateTimeMember
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Converter\Chain
 */
final class DateTimeMember extends ChainMember
{

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $format;

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return bool
     */
    protected function isProcessable(StringFormTypeInterface $stringFormType): bool
    {
        if (!preg_match(
            "/^(?P<fqClassName>\\\?(?P<className>DateTime(Immutable)?))(\(format='(?P<format>[^']+)'\))?$/",
            $stringFormType->getStringType(),
            $matches
        )) {
            return false;
        }

        $this->className = $matches['className'];
        $this->format = '';

        if (isset($matches['format'])) {
            $this->format = $matches['format'];
        }

        return true;
    }

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return TypeInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @throws LogicException
     */
    protected function createType(StringFormTypeInterface $stringFormType): TypeInterface
    {
        if ($this->format !== '') {
            return new DateTimeType($this->className, $this->format);
        }

        return new DateTimeType($this->className);
    }
}
