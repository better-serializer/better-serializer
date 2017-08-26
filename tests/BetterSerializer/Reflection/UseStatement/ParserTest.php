<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement;

use PHPUnit\Framework\TestCase;

/**
 * Class ParserTest
 * @author mfris
 * @package BetterSerializer\Reflection\UseStatement
 */
class ParserTest extends TestCase
{

    /**
     * @param string $phpSource
     * @param array $expected
     * @dataProvider dataProviderForParse
     */
    public function testParse(string $phpSource, array $expected): void
    {
        $parser = new Parser();
        $useStatements = $parser->parseUseStatements($phpSource);

        self::assertCount(count($expected), $useStatements);

        foreach ($useStatements as $key => $useStatement) {
            self::assertInstanceOf(UseStatement::class, $useStatement);
            self::assertSame($expected[$key]['fqdn'], $useStatement->getFqdn());

            if (isset($expected[$key]['alias'])) {
                self::assertSame($expected[$key]['alias'], $useStatement->getAlias());
            }
        }
    }

    /**
     * @return array
     */
    public function dataProviderForParse(): array
    {
        return [
            $this->getData1(),
            $this->getWrongData1(),
        ];
    }

    /**
     * @return array
     */
    private function getData1(): array
    {
        $source = <<<EOF
<?php 
namespace asd;

use abs;
use abc\zxc;
use abs as qwe;


use Abcd\Efgh as Ijkl;

use \absd as qwer;
use \Abcde\Efghi as Ijklm;

EOF;

        return [
            $source,
            [
                ['fqdn' => 'abs'],
                ['fqdn' => 'abc\zxc'],
                ['fqdn' => 'abs', 'alias' => 'qwe'],
                ['fqdn' => 'Abcd\Efgh', 'alias' => 'Ijkl'],
                ['fqdn' => '\absd', 'alias' => 'qwer'],
                ['fqdn' => '\Abcde\Efghi', 'alias' => 'Ijklm'],
            ]
        ];
    }

    /**
     * @return array
     */
    private function getWrongData1(): array
    {
        $source = <<<EOF
<?php 
namespace asd;

EOF;

        return [
            $source,
            []
        ];
    }
}
