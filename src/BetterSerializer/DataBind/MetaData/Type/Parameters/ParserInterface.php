<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Parameters;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;

/**
 *
 */
interface ParserInterface
{

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return ParametersInterface
     */
    public function parseParameters(StringFormTypeInterface $stringFormType): ParametersInterface;
}
