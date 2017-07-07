<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\Property\Context;

/**
 * Class StringTypedPropertyContext
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
final class DerivedStringTypedPropertyContext implements StringTypedPropertyContextInterface
{

    /**
     * @var string
     */
    private $stringType;

    /**
     * @var string
     */
    private $namespace;

    /**
     * StringTypedPropertyContext constructor.
     * @param string $stringType
     * @param string $namespace
     */
    public function __construct(string $stringType, string $namespace)
    {
        $this->stringType = $stringType;
        $this->namespace = $namespace;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getStringType(): string
    {
        return $this->stringType;
    }
}
