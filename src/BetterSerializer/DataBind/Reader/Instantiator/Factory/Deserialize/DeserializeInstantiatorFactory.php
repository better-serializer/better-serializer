<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory\Deserialize;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\InstantiatorFactoryInterface;
use BetterSerializer\DataBind\Reader\Instantiator\InstantiatorInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Deserialize\DeserializeInstantiator;
use Doctrine\Instantiator\Instantiator as DoctrineInstantiator;
use Doctrine\Instantiator\InstantiatorInterface as DoctrineInstantiatorInterface;

/**
 * Class ReflectionFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator
 */
final class DeserializeInstantiatorFactory implements InstantiatorFactoryInterface
{

    /**
     * @var DoctrineInstantiatorInterface
     */
    private $instantiator;

    /**
     * @param MetaDataInterface $metaData
     * @return InstantiatorInterface
     */
    public function newInstantiator(MetaDataInterface $metaData): InstantiatorInterface
    {
        $className = $metaData->getClassMetadata()->getClassName();

        return new DeserializeInstantiator($this->getInstantiatorInterface(), $className);
    }

    /**
     * @param MetaDataInterface $metaData
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function isApplicable(MetaDataInterface $metaData): bool
    {
        return true;
    }

    /**
     * possible todo: maybe refactor using special factory with injected Instantiator?
     *
     * @return DoctrineInstantiatorInterface
     */
    private function getInstantiatorInterface(): DoctrineInstantiatorInterface
    {
        if ($this->instantiator === null) {
            $this->instantiator = new DoctrineInstantiator();
        }

        return $this->instantiator;
    }
}
