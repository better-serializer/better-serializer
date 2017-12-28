<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;

/**
 * This processor shouldn't exist, a Simple processor should be injected into a Property procesor instead.
 * But this implementation is more performant, since there is no unnecessary sub context creation
 */
final class SimpleProperty extends NestedProcessor
{

    /**
     * @var ExtractorInterface
     */
    private $extractor;

    /**
     * @var ConverterInterface
     */
    private $converter;

    /**
     * Property constructor.
     * @param ExtractorInterface $extractor
     * @param ConverterInterface $converter
     * @param string $outputKey
     */
    public function __construct(ExtractorInterface $extractor, ConverterInterface $converter, string $outputKey)
    {
        $this->extractor = $extractor;
        $this->converter = $converter;
        parent::__construct($outputKey);
    }

    /**
     * @param ContextInterface $context
     * @param mixed $data
     */
    public function process(ContextInterface $context, $data): void
    {
        $value = $this->extractor->extract($data);
        $convertedValue = $this->converter->convert($value);
        $context->write($this->outputKey, $convertedValue);
    }
}
