<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Type\Chain;

use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\NullType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Type\ExtractorInterface;

/**
 * Class SimpleMember
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Type
 */
final class ArrayMember extends ChainMember
{

    /**
     * @var ExtractorInterface
     */
    private $extractor;

    /**
     * ArrayMember constructor.
     * @param ExtractorInterface $extractor
     */
    public function __construct(ExtractorInterface $extractor)
    {
        $this->extractor = $extractor;
    }

    /**
     * @param mixed $data
     * @return bool
     */
    protected function isProcessable($data): bool
    {
        return is_array($data);
    }

    /**
     * @param mixed $data
     * @return TypeInterface
     */
    protected function createType($data): TypeInterface
    {
        if (empty($data)) {
            return new ArrayType(new NullType());
        }

        $first = reset($data);
        $subType = $this->extractor->extract($first);

        return new ArrayType($subType);
    }
}
