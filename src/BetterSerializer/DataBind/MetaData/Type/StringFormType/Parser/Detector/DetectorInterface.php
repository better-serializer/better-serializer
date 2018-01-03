<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Detector;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ContextInterface;

/**
 *
 */
interface DetectorInterface
{

    /**
     * @param string $typeString
     * @param ContextInterface $context
     * @return ResultInterface
     */
    public function detectType(string $typeString, ContextInterface $context): ResultInterface;
}
