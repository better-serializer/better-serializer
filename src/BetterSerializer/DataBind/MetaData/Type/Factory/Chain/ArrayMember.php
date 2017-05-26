<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class SimpleMember
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 */
final class ArrayMember extends ChainMember
{

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * ArrayMember constructor.
     * @param TypeFactoryInterface $typeFactory
     */
    public function __construct(TypeFactoryInterface $typeFactory)
    {
        $this->typeFactory = $typeFactory;
    }

    /**
     * @param string $stringType
     * @return bool
     */
    protected function isProcessable(string $stringType): bool
    {
        return preg_match('/array<[^>]+>/', $stringType) ? true : false;
    }

    /**
     * @param string $stringType
     * @return TypeInterface
     */
    protected function createType(string $stringType): TypeInterface
    {
        preg_match('/array<([^>]+)>/', $stringType, $matches);
        $stringSubType = $matches[1];
        $subType = $this->typeFactory->getType($stringSubType);

        return new ArrayType($subType);
    }
}
