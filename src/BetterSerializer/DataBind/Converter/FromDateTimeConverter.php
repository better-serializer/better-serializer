<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Converter;

use BetterSerializer\DataBind\MetaData\Type\DateTimeTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use InvalidArgumentException;

/**
 * Class FromDateTimeConverter
 * @author mfris
 * @package BetterSerializer\DataBind\Converter
 */
final class FromDateTimeConverter implements TypeDependentConverterInterface
{

    /**
     * @var DateTimeTypeInterface
     */
    private $type;

    /**
     * @var string
     */
    private $className;

    /**
     * FromDateTimeConverter constructor.
     * @param TypeInterface $type
     * @throws InvalidArgumentException
     */
    public function __construct(TypeInterface $type)
    {
        if (!$type instanceof DateTimeTypeInterface) {
            throw new InvalidArgumentException(sprintf('Invalid type: %s.', get_class($type)));
        }
        $this->type = $type;
        $this->className = $type->getClassName();
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function convert($value)
    {
        if ($value === null) {
            return null;
        }

        $className = $this->className;

        if (!$value instanceof $className) {
            throw new InvalidArgumentException(sprintf(
                'Expected a "%s", got "%s".',
                $className,
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        $format = $this->type->getFormat();
        $result = $value->format($format);

        if ($result === false) {
            throw new InvalidArgumentException(sprintf('The date format "%s" is not valid.', $format));
        }

        return $result;
    }
}
