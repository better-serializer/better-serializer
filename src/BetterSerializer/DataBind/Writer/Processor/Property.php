<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Context\ContextInterface;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;

/**
 * Class Property
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
 */
final class Property extends NestedProcessor
{

    /**
     * @var ExtractorInterface
     */
    private $extractor;

    /**
     * Property constructor.
     * @param ExtractorInterface $extractor
     * @param string $outputKey
     */
    public function __construct(ExtractorInterface $extractor, string $outputKey)
    {
        $this->extractor = $extractor;
        parent::__construct($outputKey);
    }

    /**
     * @param ContextInterface $context
     * @param mixed $data
     */
    public function process(ContextInterface $context, $data): void
    {
        $value = $this->extractor->extract($data);
        $context->write($this->outputKey, $value);
    }
}
