<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Constructor\Factory;

use BetterSerializer\DataBind\MetaData\ClassMetaDataInterface;
use BetterSerializer\DataBind\Reader\Constructor\ConstructorInterface;

/**
 * Interface FactoryInterface
 * @package BetterSerializer\DataBind\Reader\Constructor\Factory
 */
interface ConstructorFactoryInterface
{

    /**
     * @param ClassMetaDataInterface $metaData
     * @return ConstructorInterface
     */
    public function newConstructor(ClassMetaDataInterface $metaData): ConstructorInterface;
}
