<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format;

/**
 *
 */
interface ResultInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string|null
     */
    public function getParameters(): ?string;

    /**
     * @return string|null
     */
    public function getNestedValueType(): ?string;

    /**
     * @return string|null
     */
    public function getNestedKeyType(): ?string;
}
