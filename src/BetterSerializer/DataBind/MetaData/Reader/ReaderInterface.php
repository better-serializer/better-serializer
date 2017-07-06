<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use LogicException;
use ReflectionException;

/**
 * Class Reader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 */
interface ReaderInterface
{
    /**
     * @param string $className
     * @return MetaDataInterface
     * @throws LogicException
     * @throws ReflectionException
     */
    public function read(string $className): MetaDataInterface;
}
