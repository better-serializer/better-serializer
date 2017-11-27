<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Parameters;

use RuntimeException;

/**
 * Class Parameters
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Parameters
 */
final class Parameters implements ParametersInterface
{

    /**
     * @var ParameterInterface[]
     */
    private $parameters;

    /**
     * Parameters constructor.
     * @param ParameterInterface[] $parameters
     */
    public function __construct(array $parameters)
    {
        $this->setParameters($parameters);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->parameters[$name]);
    }

    /**
     * @param string $name
     * @return ParameterInterface
     * @throws RuntimeException
     */
    public function get(string $name): ParameterInterface
    {
        if (!$this->has($name)) {
            throw new RuntimeException(sprintf('Parameter missing: %s', $name));
        }

        return $this->parameters[$name];
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->parameters);
    }

    /**
     * @param ParametersInterface $parameters
     * @return bool
     */
    public function equals(ParametersInterface $parameters): bool
    {
        if ($this->getCount() !== $parameters->getCount()) {
            return false;
        }

        foreach ($this->parameters as $name => $parameter) {
            if (!$parameters->has($name)) {
                return false;
            }

            $otherParameter = $parameters->get($name);

            if (!$parameter->equals($otherParameter)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return count($this->parameters);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return implode(', ', $this->parameters);
    }

    /**
     * @param ParameterInterface[] $parameters
     */
    private function setParameters(array $parameters): void
    {
        $this->parameters = [];

        foreach ($parameters as $parameter) {
            $this->parameters[$parameter->getName()] = $parameter;
        }
    }
}
