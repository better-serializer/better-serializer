<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

/**
 * Class NestedProcessor
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor
 */
abstract class NestedProcessor implements ProcessorInterface
{

    /**
     * @var string
     */
    protected $inputKey;

    /**
     * NestedProcessor constructor.
     * @param string $inputKey
     */
    public function __construct($inputKey)
    {
        $this->inputKey = $inputKey;
    }
}
