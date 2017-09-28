<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Flysystem\Plugin;

use League\Flysystem\FilesystemInterface;
use PHPUnit\Framework\TestCase;
use LogicException;
use RuntimeException;

/**
 * Class FirstXBytesTest
 * @author mfris
 * @package BetterSerializer\Flysystem\Plugin
 */
class FirstXLinesTest extends TestCase
{

    /**
     *
     */
    public function testGetFirstXLines(): void
    {
        $lines = 1;
        $path = '/test';
        $stream = fopen('php://memory', 'rw');
        $string = <<<EOF
testing test
asd
zxc
EOF;
        fwrite($stream, $string);
        rewind($stream);
        $filesystem = $this->createMock(FilesystemInterface::class);
        $filesystem->expects(self::once())
            ->method('readStream')
            ->with($path)
            ->willReturn($stream);

        /* @var $filesystem FilesystemInterface */
        $plugin = new FirstXLines($lines);
        $plugin->setFilesystem($filesystem);

        self::assertSame("testing test\n", $plugin->handle($path));
        self::assertSame('getFirstXLines', $plugin->getMethod());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Path missing.
     */
    public function testHandleThrows(): void
    {
        $lines = 7;
        $filesystem = $this->createMock(FilesystemInterface::class);
        /* @var $filesystem FilesystemInterface */
        $plugin = new FirstXLines($lines);
        $plugin->setFilesystem($filesystem);

        $plugin->handle();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage $bytes must be greater than zero.
     */
    public function testSetBytesThrows(): void
    {
        $lines = -1;
        new FirstXLines($lines);
    }
}
