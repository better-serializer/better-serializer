<?php
/**
 * @author  mfris
 */
declare(strict_types=1);

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\MetaData;
use ReflectionClass;
use LogicException;

/**
 * Class Reader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 */
final class Reader
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
     * @return MetaData
     * @throws LogicException
     */
    public function read(string $className): MetaData
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
