<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement;

/**
 * Class Parser
 * @author mfris
 * @package BetterSerializer\Reflection\UseStatement
 */
final class Parser implements ParserInterface
{

    /**
     * @param string $useStatementsString
     * @return UseStatement[]
     */
    public function parseUseStatements(string $useStatementsString): array
    {
        $foundPatterns = preg_match_all(
            "/\s+use\s(?P<fqdn>[^\s]+)(\s+as\s+(?P<alias>[^\s]+))?;/",
            $useStatementsString,
            $matches,
            PREG_SET_ORDER
        );

        if ($foundPatterns === 0) {
            return [];
        }

        return array_map(function (array $match) {
            if (isset($match['alias'])) {
                return new UseStatement($match['fqdn'], $match['alias']);
            }

            return new UseStatement($match['fqdn']);
        }, $matches);
    }
}
