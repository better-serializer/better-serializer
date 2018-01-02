<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParametersInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;

/**
 *
 */
interface ParametersParserInterface
{

    /**
     * @param string $parametersString
     * @return ParametersInterface
     */
    public function parseParameters(string $parametersString): ParametersInterface;
}
