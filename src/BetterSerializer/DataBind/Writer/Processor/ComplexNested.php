<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Context\ContextInterface;
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
     * @var Object
     */
    private $objectProcessor;

    /**
     * Property constructor.
     * @param ExtractorInterface $extractor
     * @param ComplexNestedProcessorInterface $objectProcessor
     * @param string $outputKey
     */
    public function __construct(
        ExtractorInterface $extractor,
        ComplexNestedProcessorInterface $objectProcessor,
        string $outputKey
    ) {
        parent::__construct($outputKey);
        $this->extractor = $extractor;
        $this->objectProcessor = $objectProcessor;
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
        $this->objectProcessor->process($subContext, $value);

        $context->mergeSubContext($this->outputKey, $subContext);
    }
}
