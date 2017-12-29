<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Parameters;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use RuntimeException;

/**
 *
 */
final class Parser implements ParserInterface
{

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return ParametersInterface
     * @throws RuntimeException
     */
    public function parseParameters(StringFormTypeInterface $stringFormType): ParametersInterface
    {
        $rawParameterStrings = $this->getRawParameterStrings($stringFormType);
        $parameters = [];

        foreach ($rawParameterStrings as $rawParameterString) {
            $parameters[] = $this->createParameter($rawParameterString);
        }

        return new Parameters($parameters);
    }

    /**
     * @param string $rawParameterString
     * @return ParameterInterface
     * @throws RuntimeException
     */
    private function createParameter(string $rawParameterString): ParameterInterface
    {
        $parameterData = explode('=', $rawParameterString);

        if (count($parameterData) !== 2) {
            throw new RuntimeException(sprintf('Invalid parameter count for raw parameter: %s', $rawParameterString));
        }

        [$name, $value] = $parameterData;
        $trimmedValue = trim($value, "'");

        if ($trimmedValue === $value) {
            $trimmedValue = strpos($trimmedValue, '.') !== false ? (float) $trimmedValue : (int) $trimmedValue;
        }

        return new Parameter($name, $trimmedValue);
    }

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return array
     */
    private function getRawParameterStrings(StringFormTypeInterface $stringFormType): array
    {
        $rawParameters = [];

        if (!preg_match("/\((?P<parameters>[^\)]+)\)/", $stringFormType->getStringType(), $matches)) {
            return $rawParameters;
        }

        $rawParameters = preg_split('/, */', $matches['parameters']);

        return $rawParameters;
    }
}
