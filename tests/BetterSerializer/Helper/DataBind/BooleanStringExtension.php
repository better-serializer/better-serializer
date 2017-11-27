<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Helper\DataBind;

use BetterSerializer\Common\CustomTypeExtensionInterface;
use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface as ReadContext;
use BetterSerializer\DataBind\Writer\Context\ContextInterface as WriteContext;

/**
 *
 */
final class BooleanStringExtension implements CustomTypeExtensionInterface
{

    /**
     * @var ParametersInterface
     */
    private $parameters;

    /**
     * @const string
     * @SuppressWarnings(PHPMD)
     */
    private const TYPE = 'BooleanString';

    /**
     * @const string
     * @SuppressWarnings(PHPMD)
     */
    private const TYPE_TRUE = 'yes';

    /**
     * @const string
     * @SuppressWarnings(PHPMD)
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
     * @param string $key
     * @param mixed $data
     */
    public function appendData(WriteContext $context, string $key, $data): void
    {
        $value = $data ? self::TYPE_TRUE : self::TYPE_FALSE;
        $context->write($key, $value);
    }

    /**
     * @param ReadContext $context
     * @param string $key
     * @return mixed
     */
    public function extractData(ReadContext $context, string $key)
    {
        $value = $context->getValue($key);

        return $value === self::TYPE_TRUE;
    }

    /**
     * @return string
     */
    public static function getType(): string
    {
        return self::TYPE;
    }
}
