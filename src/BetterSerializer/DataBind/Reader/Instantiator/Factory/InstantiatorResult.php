<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\Reader\Instantiator\InstantiatorInterface;

/**
 * Class InstantiatorResult
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Factory
 */
final class InstantiatorResult implements InstantiatorResultInterface
{

    /**
     * @var InstantiatorInterface
     */
    private $instantiator;

    /**
     * @var MetaDataInterface
     */
    private $updatedMetaData;

    /**
     * InstantiatorResult constructor.
     * @param InstantiatorInterface $instantiator
     * @param MetaDataInterface $updatedMetaData
     */
    public function __construct(InstantiatorInterface $instantiator, MetaDataInterface $updatedMetaData)
    {
        $this->instantiator = $instantiator;
        $this->updatedMetaData = $updatedMetaData;
    }

    /**
     * @return InstantiatorInterface
     */
    public function getInstantiator(): InstantiatorInterface
    {
        return $this->instantiator;
    }

    /**
     * @return MetaDataInterface
     */
    public function getProcessedMetaData(): MetaDataInterface
    {
        return $this->updatedMetaData;
    }
}
