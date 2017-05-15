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
final class ObjectProperty implements ProcessorInterface
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
     * @var string
     */
    private $outputKey;

    /**
     * Property constructor.
     * @param ExtractorInterface $extractor
     * @param ObjectProcessorInterface $objectProcessor
     * @param string $outputKey
     */
    public function __construct(
        ExtractorInterface $extractor,
        ObjectProcessorInterface $objectProcessor,
        string $outputKey
    ) {
        $this->extractor = $extractor;
        $this->objectProcessor = $objectProcessor;
        $this->outputKey = $outputKey;
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
