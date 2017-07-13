<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Constructor\Factory;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\Reader\Constructor\ConstructorInterface;
use BetterSerializer\DataBind\Reader\Constructor\UnserializeConstructor;
use Doctrine\Instantiator\Instantiator;
use Doctrine\Instantiator\InstantiatorInterface;

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
     * @param MetaDataInterface $metaData
     * @return ConstructorInterface
     */
    public function newConstructor(MetaDataInterface $metaData): ConstructorInterface
    {
        $className = $metaData->getClassMetadata()->getClassName();

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
