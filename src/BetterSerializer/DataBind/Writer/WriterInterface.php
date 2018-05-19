<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer;

use BetterSerializer\Common\SerializationTypeInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 *
 */
interface WriterInterface
{
    /**
     * @param mixed $data
     * @param SerializationTypeInterface $serializationType
     * @param SerializationContextInterface $context
     * @return mixed
     */
    public function writeValueAsString(
        $data,
        SerializationTypeInterface $serializationType,
        SerializationContextInterface $context
    );
}
