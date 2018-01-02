<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format;

use BetterSerializer\DataBind\MetaData\Type\TypeEnum;

/**
 *
 */
final class DocBlockArrayParser implements FormatParserInterface
{

    /**
     * @param string $typeFormat
     * @return Result|null
     */
    public function parse(string $typeFormat): ?ResultInterface
    {
        if (!preg_match('/^(?P<nestedValueType>[^\[]+)\[\]$/', $typeFormat, $matches)) {
            return null;
        }

        return new Result(TypeEnum::ARRAY, null, $matches['nestedValueType']);
    }
}
