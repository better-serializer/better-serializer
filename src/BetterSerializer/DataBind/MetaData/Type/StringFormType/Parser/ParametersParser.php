<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\Parameter;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParameterInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\Parameters;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParametersInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use RuntimeException;

/**
 *
 */
final class ParametersParser implements ParametersParserInterface
{

    /**
     * @param string $parametersString
     * @return ParametersInterface
     * @throws RuntimeException
     */
    public function parseParameters(string $parametersString): ParametersInterface
    {
        $rawParameterStrings = $this->getRawParameterStrings($parametersString);
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
        $parameterData = explode('=', trim($rawParameterString));

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
     * @param string $parametersString
     * @return array
     */
    private function getRawParameterStrings(string $parametersString): array
    {
        return array_filter(preg_split('/,\s*/', $parametersString), function (string $param) {
            return trim($param) !== '';
        });
    }
}
