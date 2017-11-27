<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Parameters;

/**
 * Interface ParameterInterface
 * @package BetterSerializer\DataBind\MetaData\Type\Parameters
 */
interface ParameterInterface
{

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param ParameterInterface $parameter
     * @return bool
     */
    public function equals(ParameterInterface $parameter): bool;

    /**
     * @return string
     */
    public function __toString(): string;
}
