<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver;

use BetterSerializer\DataBind\MetaData\Type\TypeClassEnumInterface;

/**
 *
 */
final class Result implements ResultInterface
{

    /**
     * @var string
     */
    private $typeName;

    /**
     * @var TypeClassEnumInterface
     */
    private $typeClass;

    /**
     * @param string $typeName
     * @param TypeClassEnumInterface $typeClass
     */
    public function __construct(string $typeName, TypeClassEnumInterface $typeClass)
    {
        $this->typeName = $typeName;
        $this->typeClass = $typeClass;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return $this->typeName;
    }

    /**
     * @return TypeClassEnumInterface
     */
    public function getTypeClass(): TypeClassEnumInterface
    {
        return $this->typeClass;
    }
}
