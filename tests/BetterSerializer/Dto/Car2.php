<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\Dto;

use BetterSerializer\DataBind\MetaData\Annotations as Serializer;
use JMS\Serializer\Annotation as JmsSerializer;

/**
 * Class Car
 * @author mfris
 * @package BetterSerializer\Dto
 */
class Car2
{

    /**
     * @var string
     * @Serializer\Property(name="serializedTitle", type="string")
     * @JmsSerializer\Type("string")
     */
    private $title;

    /**
     * Car2 constructor.
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
