<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Type\SimpleTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Converter\ConverterFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\SimpleProcessor;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;

/**
 *
 */
final class SimpleMember extends ChainMember
{

    /**
     * @var ConverterFactoryInterface
     */
    private $converterFactory;

    /**
     * ObjectMember constructor.
     * @param ConverterFactoryInterface $converterFactory
     */
    public function __construct(ConverterFactoryInterface $converterFactory)
    {
        $this->converterFactory = $converterFactory;
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    protected function isCreatable(TypeInterface $type): bool
    {
        return $type instanceof SimpleTypeInterface;
    }

    /**
     * @param TypeInterface $type
     * @return ProcessorInterface
     */
    protected function createProcessor(TypeInterface $type): ProcessorInterface
    {
        $converter = $this->converterFactory->newConverter($type);

        return new SimpleProcessor($converter);
    }
}
