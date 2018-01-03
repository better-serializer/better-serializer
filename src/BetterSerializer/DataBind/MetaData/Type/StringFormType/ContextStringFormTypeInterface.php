<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParametersInterface;

/**
 *
 */
interface ContextStringFormTypeInterface extends StringFormTypeInterface
{

    /**
     * @return ContextStringFormTypeInterface|null
     */
    public function getCollectionKeyType(): ?ContextStringFormTypeInterface;

    /**
     * @return ContextStringFormTypeInterface|null
     */
    public function getCollectionValueType(): ?ContextStringFormTypeInterface;

    /**
     * @return null|ParametersInterface
     */
    public function getParameters(): ?ParametersInterface;
}
