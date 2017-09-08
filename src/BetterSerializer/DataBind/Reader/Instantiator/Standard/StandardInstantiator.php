<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Standard;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Instantiator\ProcessingInstantiatorInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ComplexParamProcessorInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ParamProcessorInterface;
use BetterSerializer\Reflection\ReflectionClassInterface;
use ReflectionClass as NativeReflectionClass;
use ReflectionException;

/**
 * Class UnserializeConstructor
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator
 */
final class StandardInstantiator implements ProcessingInstantiatorInterface
{

    /**
     * @var ReflectionClassInterface
     */
    private $reflectionClass;

    /**
     * @var NativeReflectionClass
     */
    private $nativeReflectionClass;

    /**
     * @var ParamProcessorInterface[]
     */
    private $paramProcessors;

    /**
     * @var bool
     */
    private $processorsResolved = false;

    /**
     * ReflectionConstructor constructor.
     * @param ReflectionClassInterface $reflectionClass
     * @param ParamProcessorInterface[] $paramProcessors
     * @throws ReflectionException
     */
    public function __construct(ReflectionClassInterface $reflectionClass, array $paramProcessors)
    {
        $this->reflectionClass = $reflectionClass;
        $this->paramProcessors = $paramProcessors;
        $this->nativeReflectionClass = $reflectionClass->getNativeReflClass();
    }

    /**
     * @param ContextInterface $context
     * @return mixed
     */
    public function instantiate(ContextInterface $context)
    {
        $params = array_map(function (ParamProcessorInterface $paramProcessor) use ($context) {
            return $paramProcessor->processParam($context);
        }, $this->paramProcessors);

        return $this->nativeReflectionClass->newInstanceArgs($params);
    }

    /**
     *
     */
    public function resolveRecursiveProcessors(): void
    {
        if ($this->processorsResolved) {
            return;
        }

        foreach ($this->paramProcessors as $processor) {
            if ($processor instanceof ComplexParamProcessorInterface) {
                $processor->resolveRecursiveProcessors();
            }
        }

        $this->processorsResolved = true;
    }

    /**
     *
     */
    public function __wakeup()
    {
        $this->nativeReflectionClass = $this->reflectionClass->getNativeReflClass();
    }
}
