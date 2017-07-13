<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Constructor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use ReflectionClass;
use ReflectionException;

/**
 * Class UnserializeConstructor
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Constructor
 */
final class StandardConstructor implements ConstructorInterface
{

    /**
     * @var ReflectionClass
     */
    private $reflectionClass;

    /**
     * @var ProcessorInterface[]
     */
    private $processors;

    /**
     * ReflectionConstructor constructor.
     * @param ReflectionClass $reflectionClass
     * @param ProcessorInterface[] $processors
     * @throws ReflectionException
     */
    public function __construct(ReflectionClass $reflectionClass, array $processors)
    {
        $this->reflectionClass = $reflectionClass;
        $this->processors = $processors;
    }

    /**
     * @param ContextInterface $context
     * @return mixed
     */
    public function construct(ContextInterface $context)
    {
        $params = array_map(function (ProcessorInterface $processor) use ($context) {
            $processor->process($context);

            return $context->getDeserialized();
        }, $this->processors);

        return $this->reflectionClass->newInstanceArgs($params);
    }
}
