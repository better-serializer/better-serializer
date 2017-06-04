<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;

/**
 * Class ObjectProperty
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
 */
final class ComplexNested extends NestedProcessor
{

    /**
     * @var ExtractorInterface
     */
    private $extractor;

    /**
     * @var ComplexNestedProcessorInterface
     */
    private $complexNestedProcessor;

    /**
     * Property constructor.
     * @param ExtractorInterface $extractor
     * @param ComplexNestedProcessorInterface $complNestedProcessor
     * @param string $outputKey
     */
    public function __construct(
        ExtractorInterface $extractor,
        ComplexNestedProcessorInterface $complNestedProcessor,
        string $outputKey
    ) {
        parent::__construct($outputKey);
        $this->extractor = $extractor;
        $this->complexNestedProcessor = $complNestedProcessor;
    }

    /**
     * @param ContextInterface $context
     * @param mixed $data
     */
    public function process(ContextInterface $context, $data): void
    {
        if ($data === null) {
            $context->write($this->outputKey, null);

            return;
        }

        $subContext = $context->createSubContext();
        $value = $this->extractor->extract($data);
        $this->complexNestedProcessor->process($subContext, $value);

        $context->mergeSubContext($this->outputKey, $subContext);
    }
}
