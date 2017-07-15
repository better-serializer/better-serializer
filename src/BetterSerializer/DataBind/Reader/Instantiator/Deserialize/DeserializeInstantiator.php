<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Deserialize;

use BetterSerializer\DataBind\Reader\Instantiator\InstantiatorInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use Doctrine\Instantiator\InstantiatorInterface as DoctrineInstantiatorInterface;
use Doctrine\Instantiator\Exception\ExceptionInterface;

/**
 * Class UnserializeConstructor
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator
 */
final class DeserializeInstantiator implements InstantiatorInterface
{

    /**
     * @var DoctrineInstantiatorInterface
     */
    private $instantiator;

    /**
     * @var string
     */
    private $className;

    /**
     * ReflectionConstructor constructor.
     * @param DoctrineInstantiatorInterface $instantiator
     * @param string $className
     */
    public function __construct(DoctrineInstantiatorInterface $instantiator, string $className)
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
    public function instantiate(ContextInterface $context)
    {
        return $this->instantiator->instantiate($this->className);
    }
}
