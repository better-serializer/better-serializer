<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer;

/**
 * Interface SerializationContextInterface
 * @package BetterSerializer\DataBind\Writer
 */
interface SerializationContextInterface
{

    /**
     * @return string[]
     */
    public function getGroups(): array;
}
