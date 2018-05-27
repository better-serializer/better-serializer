<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Type\DateTimeTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\SimpleTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\Processor\SimpleProcessor;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;

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
     * SimpleMember constructor.
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
        return $type instanceof SimpleTypeInterface || $type instanceof DateTimeTypeInterface;
    }

    /**
     * @param TypeInterface $type
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function createProcessor(TypeInterface $type, SerializationContextInterface $context): ProcessorInterface
    {
        $converter = $this->converterFactory->newConverter($type);

        return new SimpleProcessor($converter);
    }
}
