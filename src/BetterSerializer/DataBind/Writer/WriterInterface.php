<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer;

use BetterSerializer\Common\SerializationType;
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
     * @param SerializationType $type
     * @return string
     * @throws RuntimeException
     * @throws ReflectionException
     * @throws LogicException
     */
    public function writeValueAsString($data, SerializationType $type): string;
}
