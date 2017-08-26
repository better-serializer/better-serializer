<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Type\Chain;

use BetterSerializer\DataBind\MetaData\Type\DateTimeType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use DateTime;
use DateTimeImmutable;
use LogicException;

/**
 * Class SimpleMember
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Type
 */
final class DateTimeMember extends ChainMember
{

    /**
     * @param mixed $data
     * @return bool
     */
    protected function isProcessable($data): bool
    {
        return $data instanceof DateTime || $data instanceof DateTimeImmutable;
    }

    /**
     * @param mixed $data
     * @return TypeInterface
     * @throws LogicException
     */
    protected function createType($data): TypeInterface
    {
        return new DateTimeType(get_class($data));
    }
}
