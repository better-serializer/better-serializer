<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Helper;

use BetterSerializer\Common\CollectionExtensionInterface;
use BetterSerializer\Common\CollectionAdapterInterface;
use BetterSerializer\Common\TypeExtensionInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParametersInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface as ReadContext;
use BetterSerializer\DataBind\Writer\Context\ContextInterface as WriteContext;
use PHPUnit\Framework\MockObject\Generator;
use PHPUnit_Framework_MockObject_Generator;

/**
 * @SuppressWarnings(PHPMD)
 */
final class ExtensionMockFactory
{
    /**
     * @param string $type
     * @param string $replacedType
     * @return TypeExtensionInterface
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \ReflectionException
     * @SuppressWarnings(PHPMD)
     */
    public static function createTypeExcensionMock(string $type, string $replacedType = null): TypeExtensionInterface
    {
        $mockGeneratpr = new Generator();
        $parameters = $mockGeneratpr->getMock(ParametersInterface::class);

        $typeExtension = new class($parameters) implements TypeExtensionInterface {
            private static $type;
            private static $replacedType;

            public static function getType(): string
            {
                return self::$type;
            }

            public static function getReplacedType(): ?string
            {
                return self::$replacedType;
            }

            public function __construct(ParametersInterface $parameters)
            {
            }

            public function appendData(WriteContext $context, $data): void
            {
            }

            public function extractData(ReadContext $context)
            {
            }

            public function setType(string $className)
            {
                self::$type = $className;
            }

            public function setReplacedType(?string $replacedType)
            {
                self::$replacedType = $replacedType;
            }
        };
        $typeExtension->setType($type);
        $typeExtension->setReplacedType($replacedType);

        return $typeExtension;
    }

    /**
     * @param string $type
     * @param bool $empty
     * @return CollectionExtensionInterface
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \ReflectionException
     */
    public static function createCollectionExtensionMock(string $type, bool $empty): CollectionExtensionInterface
    {
        $mockGeneratpr = new Generator();
        $parameters = $mockGeneratpr->getMock(ParametersInterface::class);

        $collectionExtension = new class($parameters) implements CollectionExtensionInterface {
            private static $type;
            private $empty;

            /**
             * @var CollectionAdapterInterface
             */
            private $iterator;

            public static function getType(): string
            {
                return self::$type;
            }

            public static function getReplacedType(): ?string
            {
                return null;
            }

            public function __construct(ParametersInterface $parameters)
            {
                $mockGeneratpr = new Generator();
                $this->iterator = $mockGeneratpr->getMock(CollectionExtensionInterface::class);
            }

            public function setType(string $className)
            {
                self::$type = $className;
            }

            public function setEmpty(bool $empty): void
            {
                $this->empty = $empty;
            }

            public function isEmpty($collection): bool
            {
                return $this->empty;
            }

            public function getAdapter($collection): CollectionAdapterInterface
            {
                return $this->iterator;
            }

            public function getNewAdapter(): CollectionAdapterInterface
            {
                return $this->iterator;
            }
        };

        $collectionExtension->setType($type);
        $collectionExtension->setEmpty($empty);

        return $collectionExtension;
    }
}
