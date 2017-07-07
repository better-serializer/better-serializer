<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Model\MetaData;
use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\Property\PropertyReaderInterface;
use ReflectionClass;
use ReflectionException;
use LogicException;

/**
 * Class Reader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 */
final class Reader implements ReaderInterface
{
    /**
     * @var ClassReaderInterface
     */
    private $classReader;

    /**
     * @var PropertyReaderInterface
     */
    private $propertyReader;

    /**
     * Reader constructor.
     * @param ClassReaderInterface $classReader
     * @param PropertyReaderInterface $propertyReader
     */
    public function __construct(
        ClassReaderInterface $classReader,
        PropertyReaderInterface $propertyReader
    ) {
        $this->classReader = $classReader;
        $this->propertyReader = $propertyReader;
    }

    /**
     * @param string $className
     * @return MetaDataInterface
     * @throws LogicException
     * @throws ReflectionException
     */
    public function read(string $className): MetaDataInterface
    {
        $reflectionClass = $this->getReflectionClass($className);
        $classMetadata = $this->classReader->getClassMetadata($reflectionClass);
        $propertyMetadata = $this->propertyReader->getPropertyMetadata($reflectionClass);

        return new MetaData($classMetadata, $propertyMetadata);
    }

    /**
     * @param string $className
     * @return ReflectionClass
     * @throws LogicException
     * @throws ReflectionException
     */
    private function getReflectionClass(string $className): ReflectionClass
    {
        $reflectionClass = new ReflectionClass($className);

        if (!$reflectionClass->isUserDefined()) {
            throw new LogicException(sprintf('Class "%s" is not user-defined', $reflectionClass->getName()));
        }

        return $reflectionClass;
    }
}
