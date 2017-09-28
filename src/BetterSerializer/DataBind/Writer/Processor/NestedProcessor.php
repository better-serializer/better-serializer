<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

/**
 * Class NestedProcessor
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
 */
abstract class NestedProcessor implements ProcessorInterface
{

    /**
     * @var string
     */
    protected $outputKey;

    /**
     * NestedProcessor constructor.
     * @param string $outputKey
     */
    public function __construct($outputKey)
    {
        $this->outputKey = $outputKey;
    }
}
