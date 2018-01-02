<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format;

/**
 *
 */
interface ParserChainInterface
{

    /**
     * @param string $typeFormat
     * @return Result
     */
    public function parse(string $typeFormat): ResultInterface;
}
