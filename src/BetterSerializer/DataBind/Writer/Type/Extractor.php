<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Type;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Type\Chain\ChainMemberInterface;
use LogicException;

/**
 * Class StringTypeExtractor
 * @author mfris
 * @package BetterSerializer\DataBind\Writer
 */
final class Extractor implements ExtractorInterface
{

    /**
     * @var ChainMemberInterface[]
     */
    private $chainMembers;

    /**
     * Chain constructor.
     * @param ChainMemberInterface[] $chainMembers
     */
    public function __construct(array $chainMembers = [])
    {
        $this->chainMembers = $chainMembers;
    }

    /**
     * @param $data
     * @return TypeInterface
     * @throws LogicException
     */
    public function extract($data): TypeInterface
    {
        foreach ($this->chainMembers as $chainMember) {
            $type = $chainMember->getType($data);

            if ($type) {
                return $type;
            }
        }

        throw new LogicException("Unknown type - '" . gettype($data) . "'");
    }

    /**
     * @param ChainMemberInterface $chainMember
     */
    public function addChainMember(ChainMemberInterface $chainMember): void
    {
        $this->chainMembers[] = $chainMember;
    }
}
