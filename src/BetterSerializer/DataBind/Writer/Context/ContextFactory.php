<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Context;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\Common\SerializationTypeInterface;
use BetterSerializer\DataBind\Writer\Context\Json\Context as JsonContext;
use BetterSerializer\DataBind\Writer\Context\PhpArray\Context as PhpArrayContext;
use RuntimeException;

/**
 *
 */
final class ContextFactory implements ContextFactoryInterface
{

    /**
     * @const string[string]
     */
    private const TYPE_2_FACTORY_MAPPING = [
        SerializationType::JSON => JsonContext::class,
        SerializationType::PHP_ARRAY => PhpArrayContext::class,
    ];

    /**
     * @param SerializationTypeInterface $serializationType
     * @return ContextInterface
     * @throws RuntimeException
     */
    public function createContext(SerializationTypeInterface $serializationType): ContextInterface
    {
        /* @var $serialization string */
        $serialization = $serializationType->getValue();

        if (!isset(self::TYPE_2_FACTORY_MAPPING[$serialization])) {
            throw new RuntimeException(sprintf('Invalid serialization type: %s', $serialization));
        }

        $contextClass = self::TYPE_2_FACTORY_MAPPING[$serialization];

        return new $contextClass();
    }
}
