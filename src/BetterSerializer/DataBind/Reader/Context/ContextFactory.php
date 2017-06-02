<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Context;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\DataBind\Reader\Context\Json\Context as JsonContext;
use RuntimeException;

/**
 * Class ContextFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Context
 */
final class ContextFactory implements ContextFactoryInterface
{

    /**
     * @const string[string]
     */
    private const TYPE_2_FACTORY_MAPPING = [
        SerializationType::JSON => JsonContext::class,
    ];

    /**
     * @param string $serialized
     * @param SerializationType $serializationType
     * @return ContextInterface
     * @throws RuntimeException
     */
    public function createContext(string $serialized, SerializationType $serializationType): ContextInterface
    {
        /* @var $serialization string */
        $serialization = $serializationType->getValue();

        if (!isset(self::TYPE_2_FACTORY_MAPPING[$serialization])) {
            throw new RuntimeException(sprintf('Invalid serialization type: %s', $serialization));
        }

        $contextClass = self::TYPE_2_FACTORY_MAPPING[$serialization];

        return new $contextClass($serialized);
    }
}
