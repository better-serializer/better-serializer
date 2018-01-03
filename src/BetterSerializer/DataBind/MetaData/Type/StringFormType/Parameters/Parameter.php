<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters;

/**
 * @package BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters
 */
final class Parameter implements ParameterInterface
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param ParameterInterface $parameter
     * @return bool
     */
    public function equals(ParameterInterface $parameter): bool
    {
        return $this->name === $parameter->getName() && $this->value === $parameter->getValue();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s="%s"', $this->name, $this->value);
    }
}
