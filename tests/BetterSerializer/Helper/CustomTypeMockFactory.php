<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Helper;

use BetterSerializer\Common\CustomTypeExtensionInterface;
use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface as ReadContext;
use BetterSerializer\DataBind\Writer\Context\ContextInterface as WriteContext;
use PHPUnit_Framework_MockObject_Generator;

/**
 *
 */
final class CustomTypeMockFactory
{
    /**
     * @param string $type
     * @return CustomTypeExtensionInterface
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit_Framework_MockObject_RuntimeException
     * @SuppressWarnings(PHPMD)
     */
    public static function createCustomTypeExcensionMock(string $type): CustomTypeExtensionInterface
    {
        $mockGeneratpr = new PHPUnit_Framework_MockObject_Generator();
        $parameters = $mockGeneratpr->getMock(ParametersInterface::class);

        $customObject = new class($parameters) implements CustomTypeExtensionInterface {
            private static $type;

            public static function getType(): string
            {
                return self::$type;
            }

            public function __construct(ParametersInterface $parameters)
            {
            }

            public function appendData(WriteContext $context, string $key, $data): void
            {
            }

            public function extractData(ReadContext $context, string $key)
            {
            }

            public function setType(string $className)
            {
                self::$type = $className;
            }
        };
        $customObject->setType($type);

        return $customObject;
    }
}
