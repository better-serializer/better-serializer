<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Constructor;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;

/**
 * Interface ConstructorInterface
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Constructor
 */
interface ConstructorInterface
{

    /**
     * @return mixed
     */
    public function construct();
}
