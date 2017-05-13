<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use BetterSerializer\DataBind\Writer\ValueWriter\ValueWriterInterface;

/**
 * Class Property
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
 */
final class Property implements ProcessorInterface
{

    /**
     * @var ExtractorInterface
     */
    private $extractor;

    /**
     * @var ValueWriterInterface
     */
    private $valueWriter;

    /**
     * Property constructor.
     * @param ExtractorInterface $extractor
     * @param ValueWriterInterface $valueWriter
     */
    public function __construct(ExtractorInterface $extractor, ValueWriterInterface $valueWriter)
    {
        $this->extractor = $extractor;
        $this->valueWriter = $valueWriter;
    }

    /**
     * @param ContextInterface $context
     * @param mixed $data
     */
    public function process(ContextInterface $context, $data): void
    {
        $value = $this->extractor->extract($data);
        $this->valueWriter->writeValue($context, $value);
    }
}
