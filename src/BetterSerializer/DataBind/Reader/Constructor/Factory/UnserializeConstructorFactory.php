<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Constructor\Factory;

use BetterSerializer\DataBind\MetaData\ClassMetaDataInterface;
use BetterSerializer\DataBind\Reader\Constructor\ConstructorInterface;
use BetterSerializer\DataBind\Reader\Constructor\UnserializeConstructor;
use Doctrine\Instantiator\Instantiator;
use Doctrine\Instantiator\InstantiatorInterface;
use Doctrine\Instantiator\Exception\ExceptionInterface;

/**
 * Class ReflectionFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Constructor
 */
final class UnserializeConstructorFactory implements ConstructorFactoryInterface
{

    /**
     * @var InstantiatorInterface
     */
    private $instantiator;

    /**
     * @param ClassMetaDataInterface $metaData
     * @return ConstructorInterface
     * @throws ExceptionInterface
     */
    public function newConstructor(ClassMetaDataInterface $metaData): ConstructorInterface
    {
        $className = $metaData->getClassName();

        return new UnserializeConstructor($this->getInstantiatorInterface(), $className);
    }

    /**
     * possible todo: maybe refactor using special factory with injected Instantiator?
     *
     * @return InstantiatorInterface
     */
    private function getInstantiatorInterface(): InstantiatorInterface
    {
        if ($this->instantiator === null) {
            $this->instantiator = new Instantiator();
        }

        return $this->instantiator;
    }
}
