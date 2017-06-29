<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use Iterator;

/**
 * Class Object
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
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
     * @param mixed $data
     */
    public function process(ContextInterface $context, $data): void
    {
        if (empty($data)) {
            return;
        }

        /* @var $data Iterator */
        foreach ($data as $key => $value) {
            $convertedValue = $this->converter->convert($value);
            $context->write($key, $convertedValue);
        }
    }
}
