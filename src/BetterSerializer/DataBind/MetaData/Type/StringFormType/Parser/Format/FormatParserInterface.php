<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format;

/**
 *
 */
interface FormatParserInterface
{

    /**
     * @param string $typeFormat
     * @return Result|null
     */
    public function parse(string $typeFormat): ?ResultInterface;
}
