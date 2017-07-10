<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Model\MetaData;
use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ClassReader\ClassReaderInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\ConstructorParamsReaderInterface;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\PropertiesReaderInterface;
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
     * @var PropertiesReaderInterface
     */
    private $propertiesReader;

    /**
     * @var ConstructorParamsReaderInterface
     */
    private $constrParamsReader;

    /**
     * Reader constructor.
     * @param ClassReaderInterface $classReader
     * @param PropertiesReaderInterface $propertyReader
     * @param ConstructorParamsReaderInterface $constrParamsReader
     */
    public function __construct(
        ClassReaderInterface $classReader,
        PropertiesReaderInterface $propertyReader,
        ConstructorParamsReaderInterface $constrParamsReader
    ) {
        $this->classReader = $classReader;
        $this->propertiesReader = $propertyReader;
        $this->constrParamsReader = $constrParamsReader;
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
        $propertyMetadata = $this->propertiesReader->getPropertiesMetadata($reflectionClass);
        $constrParamsMetaData =
            $this->constrParamsReader->getConstructorParamsMetadata($reflectionClass, $propertyMetadata);

        return new MetaData($classMetadata, $propertyMetadata, $constrParamsMetaData);
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
