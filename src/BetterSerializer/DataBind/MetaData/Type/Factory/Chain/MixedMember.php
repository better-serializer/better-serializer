<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\NullType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\MetaData\Type\UnknownType;
use LogicException;
use RuntimeException;

/**
 * Class SimpleMember
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Converter\Chain
 */
final class MixedMember extends ChainMember
{

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * MixedMember constructor.
     * @param TypeFactoryInterface $typeFactory
     */
    public function __construct(TypeFactoryInterface $typeFactory)
    {
        $this->typeFactory = $typeFactory;
    }

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return bool
     */
    protected function isProcessable(StringFormTypeInterface $stringFormType): bool
    {
        return strpos($stringFormType->getStringType(), '|') !== false;
    }

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return TypeInterface
     * @throws RuntimeException
     * @throws LogicException
     */
    protected function createType(StringFormTypeInterface $stringFormType): TypeInterface
    {
        if (!$stringFormType instanceof ContextStringFormTypeInterface) {
            throw new RuntimeException('String form type needs to be of ContextStringFormTypeInterface.');
        }
        $stringTypes = explode('|', $stringFormType->getStringType());

        foreach ($stringTypes as $stringType) {
            $newStringFormType = new ContextStringFormType(trim($stringType), $stringFormType->getReflectionClass());
            $type = $this->typeFactory->getType($newStringFormType);

            if (!$type instanceof NullType && !$type instanceof UnknownType) {
                return $type;
            }
        }

        throw new LogicException('Invalid mixed type: ' . $stringFormType->getStringType());
    }
}
