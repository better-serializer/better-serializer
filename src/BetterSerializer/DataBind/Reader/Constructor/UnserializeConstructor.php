<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Constructor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use Doctrine\Instantiator\InstantiatorInterface;
use Doctrine\Instantiator\Exception\ExceptionInterface;

/**
 * Class UnserializeConstructor
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Constructor
 */
final class UnserializeConstructor implements ConstructorInterface
{

    /**
     * @var InstantiatorInterface
     */
    private $instantiator;

    /**
     * @var string
     */
    private $className;

    /**
     * ReflectionConstructor constructor.
     * @param InstantiatorInterface $instantiator
     * @param string $className
     */
    public function __construct(InstantiatorInterface $instantiator, string $className)
    {
        $this->instantiator = $instantiator;
        $this->className = $className;
    }

    /**
     * @param ContextInterface $context
     * @return mixed
     * @throws ExceptionInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function construct(ContextInterface $context)
    {
        return $this->instantiator->instantiate($this->className);
    }
}
