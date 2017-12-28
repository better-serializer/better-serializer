<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\Writer\Context\ContextInterface;

/**
 * Class Property
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
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
     * @param mixed $data
     */
    public function process(ContextInterface $context, $data): void
    {
        $convertedValue = $this->converter->convert($data);
        $context->writeSimple($convertedValue);
    }
}
