<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\Dto;

use BetterSerializer\DataBind\MetaData\Annotations as Serializer;
use JMS\Serializer\Annotation as JmsSerializer;
use DateTime;
use DateTimeImmutable;

/**
 *
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
     * @var DateTime
     * @Serializer\Property(type="DateTime(format='Y-m-d H:i:s')")
     */
    private $manufactured;

    /**
     * @var DateTime
     */
    private $selled;

    /**
     * @var DateTimeImmutable
     */
    private $serviced;

    /**
     * @var DateTimeImmutable
     */
    private $dismantled;

    /**
     * Car2 constructor.
     * @param string $title
     * @param DateTime $manufactured
     * @param DateTime $selled = null,
     * @param DateTimeImmutable $serviced = null,
     * @param DateTimeImmutable $dismantled = null
     */
    public function __construct(
        string $title,
        DateTime $manufactured,
        DateTime $selled = null,
        DateTimeImmutable $serviced = null,
        DateTimeImmutable $dismantled = null
    ) {
        $this->title = $title;
        $this->manufactured = $manufactured;
        $this->selled = $selled;
        $this->serviced = $serviced;
        $this->dismantled = $dismantled;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return DateTime
     */
    public function getManufactured(): DateTime
    {
        return $this->manufactured;
    }

    /**
     * @return DateTime
     */
    public function getSelled(): DateTime
    {
        return $this->selled;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getServiced(): DateTimeImmutable
    {
        return $this->serviced;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDismantled(): DateTimeImmutable
    {
        return $this->dismantled;
    }
}
