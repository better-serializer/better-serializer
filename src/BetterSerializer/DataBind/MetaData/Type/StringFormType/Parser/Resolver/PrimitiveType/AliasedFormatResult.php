<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\PrimitiveType;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ResultInterface as FormatResultInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;

/**
 *
 */
final class AliasedFormatResult implements FormatResultInterface
{

    /**
     * @var FormatResultInterface
     */
    private $delegate;

    /**
     * @var string[]
     */
    private static $aliases = [
        'integer' => TypeEnum::INTEGER_TYPE,
    ];

    /**
     * @var string|null
     */
    private $resolvedType;

    /**
     * @param FormatResultInterface $delegate
     */
    public function __construct(FormatResultInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        if ($this->resolvedType === null) {
            $type = $this->delegate->getType();
            $this->resolvedType = self::$aliases[$type] ?? $type;
        }

        return $this->resolvedType;
    }

    /**
     * @return null|string
     */
    public function getParameters(): ?string
    {
        return $this->delegate->getParameters();
    }

    /**
     * @return null|string
     */
    public function getNestedValueType(): ?string
    {
        return $this->delegate->getNestedValueType();
    }

    /**
     * @return null|string
     */
    public function getNestedKeyType(): ?string
    {
        return $this->delegate->getNestedKeyType();
    }
}
