<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ContextInterface;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class PassThroughGuesserTest extends TestCase
{

    /**
     * @param string $potentialHigherClass
     * @param string $expectedGuess
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @dataProvider guessDataProvider
     */
    public function testGuess(string $potentialHigherClass, string $expectedGuess): void
    {
        $context = $this->createMock(ContextInterface::class);

        $guesser = new PassThroughGuesser();
        $guess = $guesser->guess($potentialHigherClass, $context);

        self::assertSame($expectedGuess, $guess);
    }

    /**
     * @return array
     */
    public function guessDataProvider(): array
    {
        return [
            [Car::class, trim(Car::class, '\\')],
            ['\\MyType', 'MyType'],
            ['\\MyType\\', 'MyType'],
            ['\\Namespace\\CoolClass', 'Namespace\\CoolClass'],
        ];
    }
}
