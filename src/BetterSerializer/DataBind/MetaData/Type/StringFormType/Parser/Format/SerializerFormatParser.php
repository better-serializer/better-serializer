<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format;

/**
 *
 */
final class SerializerFormatParser implements FormatParserInterface
{

    /**
     * @param string $typeFormat
     * @return Result|null
     */
    public function parse(string $typeFormat): ?ResultInterface
    {
        if (!preg_match(
            "/^\s*(?P<type>[a-zA-Z0-9_\\\]+[^\\\])\s*(<\s*(?P<keyOrValue>[A-Za-z][A-Za-z0-9_\\\]+[^\\\])"
            . "(\s*,\s*(?P<value>[A-Za-z][A-Za-z0-9_\\\]+[^\\\]))?\s*>)?"
            . "(\(\s*(?P<parameters>[^\)]*)\s*\))?$/",
            $typeFormat,
            $matches
        )) {
            return null;
        }

        $parameters = (isset($matches['parameters']) && trim($matches['parameters']) !== '') ?
                        trim($matches['parameters']) : null;
        $nestedKeyType = null;
        $nestedValueType = isset($matches['keyOrValue']) ? trim($matches['keyOrValue']) : null;

        if (isset($matches['value']) && trim($matches['value']) !== '') {
            $nestedKeyType = $nestedValueType;
            $nestedValueType = trim($matches['value']);
        }

        return new Result($matches['type'], $parameters, $nestedValueType, $nestedKeyType);
    }
}
