<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory;

use BetterSerializer\DataBind\MetaData\Type\Factory\Chain\ArrayMember;
use BetterSerializer\DataBind\MetaData\Type\Factory\Chain\DateTimeMember;
use BetterSerializer\DataBind\MetaData\Type\Factory\Chain\DocBlockArrayMember;
use BetterSerializer\DataBind\MetaData\Type\Factory\Chain\MixedMember;
use BetterSerializer\DataBind\MetaData\Type\Factory\Chain\ObjectMember;
use BetterSerializer\DataBind\MetaData\Type\Factory\Chain\SimpleMember;

/**
 * Class TypeFactoryBuilder
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Converter
 */
final class TypeFactoryBuilder
{

    /**
     * @return TypeFactoryInterface
     */
    public function build(): TypeFactoryInterface
    {
        $typeFactory = new TypeFactory();
        $typeFactory->addChainMember(new SimpleMember());
        $typeFactory->addChainMember(new DateTimeMember());
        $typeFactory->addChainMember(new ObjectMember());
        $typeFactory->addChainMember(new ArrayMember($typeFactory));
        $typeFactory->addChainMember(new DocBlockArrayMember($typeFactory));
        $typeFactory->addChainMember(new MixedMember($typeFactory));

        return $typeFactory;
    }
}
