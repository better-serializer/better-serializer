<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Helper\DataBind;

use BetterSerializer\Common\TypeExtensionInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParametersInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\DataBind\Reader\Context\ContextInterface as ReadContext;
use BetterSerializer\DataBind\Writer\Context\ContextInterface as WriteContext;

/**
 *
 */
final class BooleanStringExtension implements TypeExtensionInterface
{

    /**
     * @var ParametersInterface
     */
    private $parameters;

    /**
     * @const string
     */
    private const TYPE = 'BooleanString';

    /**
     * @const string
     */
    private const TYPE_TRUE = 'yes';

    /**
     * @const string
     */
    private const TYPE_FALSE = 'no';

    /**
     * @param ParametersInterface $parameters
     */
    public function __construct(ParametersInterface $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param WriteContext $context
     * @param mixed $data
     */
    public function appendData(WriteContext $context, $data): void
    {
        $value = $data ? self::TYPE_TRUE : self::TYPE_FALSE;
        $context->writeSimple($value);
    }

    /**
     * @param ReadContext $context
     * @return mixed
     */
    public function extractData(ReadContext $context)
    {
        $value = $context->getCurrentValue();

        return $value === self::TYPE_TRUE;
    }

    /**
     * @return string
     */
    public static function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return null|string
     */
    public static function getReplacedType(): ?string
    {
        return TypeEnum::BOOLEAN_TYPE;
    }
}
