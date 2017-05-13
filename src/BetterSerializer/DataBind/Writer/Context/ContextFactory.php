<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Context;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\DataBind\Writer\Context\Json\Context as JsonContext;
use RuntimeException;

/**
 * Class ContextFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Context
 */
final class ContextFactory implements ContextFactoryInterface
{

    /**
     * @var string[string]
     */
    private static $type2FactoryMapping = [
        SerializationType::JSON => JsonContext::class,
    ];

    /**
     * @param SerializationType $serializationType
     * @return ContextInterface
     * @throws RuntimeException
     */
    public function createContext(SerializationType $serializationType): ContextInterface
    {
        /* @var $serialization string */
        $serialization = $serializationType->getValue();

        if (!isset(self::$type2FactoryMapping[$serialization])) {
            throw new RuntimeException(sprintf('Invalid serialization type: %s', $serialization));
        }

        $contextClass = self::$type2FactoryMapping[$serialization];

        return new $contextClass();
    }
}
