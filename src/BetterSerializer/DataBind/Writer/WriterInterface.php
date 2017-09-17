<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer;

use BetterSerializer\Common\SerializationTypeInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class Writer
 * @author mfris
 * @package BetterSerializer\DataBind
 */
interface WriterInterface
{
    /**
     * @param mixed $data
     * @param SerializationTypeInterface $serializationType
     * @param SerializationContextInterface $context
     * @return string
     * @throws RuntimeException
     * @throws ReflectionException
     * @throws LogicException
     */
    public function writeValueAsString(
        $data,
        SerializationTypeInterface $serializationType,
        SerializationContextInterface $context
    ): string;
}
