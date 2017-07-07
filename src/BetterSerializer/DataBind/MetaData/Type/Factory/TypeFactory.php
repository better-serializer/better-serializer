<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory;

use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\StringTypedPropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\Chain\ChainMemberInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use LogicException;

/**
 * Class Chain
 * @author mfris
 * @package BetterSerializer\Common\Chain
 */
final class TypeFactory implements TypeFactoryInterface
{

    /**
     * @var ChainMemberInterface[]
     */
    private $chainMembers;

    /**
     * Chain constructor.
     * @param ChainMemberInterface[] $chainMembers
     */
    public function __construct(array $chainMembers = [])
    {
        $this->chainMembers = $chainMembers;
    }

    /**
     * @param StringTypedPropertyContextInterface $context
     * @return TypeInterface
     * @throws LogicException
     */
    public function getType(StringTypedPropertyContextInterface $context): TypeInterface
    {
        foreach ($this->chainMembers as $chainMember) {
            $type = $chainMember->getType($context);

            if ($type) {
                return $type;
            }
        }

        throw new LogicException("Unknown type - '{$context->getStringType()}'");
    }

    /**
     * @param ChainMemberInterface $chainMember
     */
    public function addChainMember(ChainMemberInterface $chainMember): void
    {
        $this->chainMembers[] = $chainMember;
    }
}
