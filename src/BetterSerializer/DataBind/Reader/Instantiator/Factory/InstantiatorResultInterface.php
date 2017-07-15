<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\Reader\Instantiator\InstantiatorInterface;

/**
 * Interface InstantiatorResultInterface
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Factory
 */
interface InstantiatorResultInterface
{

    /**
     * @return InstantiatorInterface
     */
    public function getInstantiator(): InstantiatorInterface;

    /**
     * @return MetaDataInterface
     */
    public function getProcessedMetaData(): MetaDataInterface;
}
