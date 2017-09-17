<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Annotations;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Required;
use RuntimeException;

/**
 * Class Property
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Annotations
 * @Annotation
 * @Target("PROPERTY")
 * @Attributes({
 *   @Attribute(BetterSerializer\DataBind\MetaData\Annotations\Groups::KEY_GROUPS, type="array<string>"),
 * })
 */
final class Groups extends AbstractAnnotation implements GroupsInterface
{

    /**
     * @const string
     */
    public const ANNOTATION_NAME = 'Groups';

    /**
     * @const string
     */
    public const DEFAULT_GROUP = 'default';

    /**
     * @const string
     */
    const KEY_GROUPS = 'groups';

    /**
     * @var string[]
     * @Required()
     */
    private $groups;

    /**
     * Groups constructor.
     * @param string[] $groups
     */
    public function __construct(array $groups = ['value' => [self::DEFAULT_GROUP]])
    {
        if (!isset($groups['value'])) {
            throw new RuntimeException('Value key missing for groups.');
        }

        $this->groups = $groups['value'];
    }


    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
}
