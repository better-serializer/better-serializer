<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format;

/**
 *
 */
final class Result implements ResultInterface
{

    /**
     * @var string
     */
    private $type;

    /**
     * @var string|null
     */
    private $parameters;

    /**
     * @var string|null
     */
    private $nestedValueType;

    /**
     * @var string|null
     */
    private $nestedKeyType;

    /**
     * @param string $type
     * @param string|null $parameters
     * @param string|null $nestedValueType
     * @param string|null $nestedKeyType
     */
    public function __construct(
        string $type,
        ?string $parameters = null,
        ?string $nestedValueType = null,
        ?string $nestedKeyType = null
    ) {
        $this->type = $type;
        $this->parameters = $parameters;
        $this->nestedValueType = $nestedValueType;
        $this->nestedKeyType = $nestedKeyType;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getParameters(): ?string
    {
        return $this->parameters;
    }

    /**
     * @return string|null
     */
    public function getNestedValueType(): ?string
    {
        return $this->nestedValueType;
    }

    /**
     * @return string|null
     */
    public function getNestedKeyType(): ?string
    {
        return $this->nestedKeyType;
    }
}
