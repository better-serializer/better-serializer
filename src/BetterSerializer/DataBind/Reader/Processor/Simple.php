<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;

/**
 *
 */
final class Simple implements ProcessorInterface
{

    /**
     * @var ConverterInterface
     */
    private $converter;

    /**
     * Property constructor.
     * @param ConverterInterface $converter
     */
    public function __construct(ConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    /**
     * @param ContextInterface $context
     */
    public function process(ContextInterface $context): void
    {
        $value = $context->getCurrentValue();
        $convertedValue = $this->converter->convert($value);
        $context->setDeserialized($convertedValue);
    }
}
