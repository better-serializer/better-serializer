<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use Iterator;

/**
 * Class Object
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor
 */
final class SimpleCollection implements CollectionProcessorInterface
{

    /**
     * @var ConverterInterface
     */
    private $converter;

    /**
     * SimpleCollection constructor.
     * @param ConverterInterface $converter
     */
    public function __construct(ConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    /**
     * @param ContextInterface $context
     * @internal param mixed $data
     */
    public function process(ContextInterface $context): void
    {
        $data = $context->getCurrentValue();
        $deserialized = [];

        if (empty($data)) {
            $context->setDeserialized($deserialized);
            return;
        }

        /* @var $data Iterator */
        foreach ($data as $key => $value) {
            $convertedValue = $this->converter->convert($value);
            $deserialized[$key] = $convertedValue;
        }

        $context->setDeserialized($deserialized);
    }
}
