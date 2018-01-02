<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format;

/**
 *
 */
final class ParserChain implements ParserChainInterface
{

    /**
     * @var FormatParserInterface[]
     */
    private $formatParsers;

    /**
     * @param FormatParserInterface[] $formatParsers
     */
    public function __construct(array $formatParsers)
    {
        $this->formatParsers = $formatParsers;
    }

    /**
     * @param string $typeFormat
     * @return Result
     */
    public function parse(string $typeFormat): ResultInterface
    {
        foreach ($this->formatParsers as $subParser) {
            $result = $subParser->parse($typeFormat);

            if ($result) {
                return $result;
            }
        }

        return new Result($typeFormat);
    }
}
