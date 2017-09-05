<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use RuntimeException;

/**
 * Class ObjectProperty
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
 */
final class ComplexNested extends NestedProcessor implements ComplexNestedProcessorInterface
{

    /**
     * @var ExtractorInterface
     */
    private $extractor;

    /**
     * @var ComplexNestedProcessorInterface|CollectionProcessorInterface|CachedProcessorInterface|ProcessorInterface
     */
    private $processor;

    /**
     * Property constructor.
     * @param ExtractorInterface $extractor
     * @param ProcessorInterface $processor
     * @param string $outputKey
     * @throws RuntimeException
     */
    public function __construct(
        ExtractorInterface $extractor,
        ProcessorInterface $processor,
        string $outputKey
    ) {
        if (!$processor instanceof ComplexNestedProcessorInterface
            && !$processor instanceof CollectionProcessorInterface
            && !$processor instanceof CachedProcessorInterface) {
            throw new RuntimeException(
                sprintf('Unexpected processor instance: %s', get_class($processor))
            );
        }

        parent::__construct($outputKey);
        $this->extractor = $extractor;
        $this->processor = $processor;
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

        if ($value === null) {
            $context->write($this->outputKey, null);

            return;
        }

        $this->processor->process($subContext, $value);

        $context->mergeSubContext($this->outputKey, $subContext);
    }

    /**
     * @throws RuntimeException
     */
    public function resolveRecursiveProcessors(): void
    {
        if (!$this->processor instanceof CachedProcessorInterface) {
            return;
        }

        $this->processor = $this->processor->getProcessor();

        if (!$this->processor instanceof ComplexNestedProcessorInterface) {
            throw new RuntimeException(
                sprintf('Unexpected processor instance: %s', get_class($this->processor))
            );
        }

        $this->processor->resolveRecursiveProcessors();
    }
}
