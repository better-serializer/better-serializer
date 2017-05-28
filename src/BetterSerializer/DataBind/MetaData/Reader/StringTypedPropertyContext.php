<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

/**
 * Class StringTypedPropertyContext
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
final class StringTypedPropertyContext implements StringTypedPropertyContextInterface
{

    /**
     * @var PropertyContextInterface
     */
    private $propertyContext;

    /**
     * @var string
     */
    private $stringType;

    /**
     * StringTypedPropertyContext constructor.
     * @param PropertyContextInterface $propertyContext
     * @param string $stringType
     */
    public function __construct(PropertyContextInterface $propertyContext, string $stringType)
    {
        $this->propertyContext = $propertyContext;
        $this->stringType = $stringType;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->propertyContext->getNamespace();
    }

    /**
     * @return string
     */
    public function getStringType(): string
    {
        return $this->stringType;
    }
}
