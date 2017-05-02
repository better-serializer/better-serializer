<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\ValueWriter;

use BetterSerializer\DataBind\Writer\Context\ContextInterface;

/**
 * Class Property
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\ValueWriter
 */
final class Property implements ValueWriterInterface
{

    /**
     * @var string
     */
    private $outputKey;

    /**
     * Property constructor.
     * @param string $outputKey
     */
    public function __construct(string $outputKey)
    {
        $this->outputKey = $outputKey;
    }

    /**
     * @param ContextInterface $context
     * @param mixed $value
     */
    public function writeValue(ContextInterface $context, $value): void
    {
        $context->write($this->outputKey, $value);
    }
}
