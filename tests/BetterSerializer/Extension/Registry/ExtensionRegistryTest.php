<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Extension\Registry;

use BetterSerializer\Dto\Car;
use BetterSerializer\Extension\Registry\Registrator\ExtensionRegistratorInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 *
 */
class ExtensionRegistryTest extends TestCase
{

    /**
     *
     */
    public function testRegisterExtension(): void
    {
        $extensionClass = Car::class;

        $registrator = $this->createMock(ExtensionRegistratorInterface::class);
        $registrator->expects(self::once())
            ->method('register')
            ->willReturn(true);

        $registry = new ExtensionRegistry([$registrator]);
        $registry->registerExtension($extensionClass);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testRegisterExtensionThrowsException(): void
    {
        $this->expectExceptionMessageRegExp(
            "/Class '[A-za-z0-9_]+', doesn't implement any of these configured extension interfaces: [A-za-z0-9_, ]+/"
        );

        $registrator = $this->createMock(ExtensionRegistratorInterface::class);
        $registrator->expects(self::once())
            ->method('register')
            ->willReturn(false);
        $registrator->expects(self::once())
            ->method('getExtTypeInterface')
            ->willReturn('TestExtensionInterface');

        $registry = new ExtensionRegistry([$registrator]);
        $registry->registerExtension(Car::class);
    }
}
