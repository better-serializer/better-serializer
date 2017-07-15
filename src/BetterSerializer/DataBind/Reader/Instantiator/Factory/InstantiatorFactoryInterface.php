<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\Reader\Instantiator\InstantiatorInterface;

/**
 * Interface FactoryInterface
 * @package BetterSerializer\DataBind\Reader\Instantiator\Factory
 */
interface InstantiatorFactoryInterface
{

    /**
     * @param MetaDataInterface $metaData
     * @return InstantiatorInterface
     */
    public function newInstantiator(MetaDataInterface $metaData): InstantiatorInterface;

    /**
     * @param MetaDataInterface $metaData
     * @return bool
     */
    public function isApplicable(MetaDataInterface $metaData): bool;
}
