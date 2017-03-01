<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Annotations;

/**
 * Interface PropertyInterface
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Annotations
 */
interface PropertyInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getType(): string;
}
