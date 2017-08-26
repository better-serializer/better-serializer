<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

use DateTime;
use DateTimeImmutable;
use LogicException;

/**
 * Class String
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
final class DateTimeType extends AbstractObjectType implements DateTimeTypeInterface
{

    /**
     * @var string
     */
    private $format;

    /**
     * @var bool[]
     */
    private static $allowedClasses = [
        DateTime::class => true,
        DateTimeImmutable::class => true,
    ];

    /**
     * StringDataType constructor.
     * @param string $className
     * @param string $format
     * @throws LogicException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct(string $className, string $format = DateTime::ATOM)
    {
        if (!isset(self::$allowedClasses[$className])) {
            throw new LogicException(sprintf("Unsupported class: '%s'.", $className));
        }

        parent::__construct($className);
        $this->format = $format;
    }

    /**
     * @return void
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function initType(): void
    {
        $this->type = TypeEnum::DATETIME();
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function equals(TypeInterface $type): bool
    {
        /* @var $type DateTimeType */
        return $type instanceof DateTimeType && parent::equals($type) && $this->format === $type->getFormat();
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function isCompatibleWith(TypeInterface $type): bool
    {
        return (
            ($type instanceof DateTimeType && $this->getClassName() === $type->getClassName())
            || parent::isCompatibleWith($type)
            || $type instanceof UnknownType
        );
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return ((string) $this->type->getValue())
            . "(class='" . $this->getClassName() . "', format='". $this->format . "')";
    }
}
