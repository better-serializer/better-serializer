<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\Dto;

use DateTime;
use DateTimeImmutable;
use JMS\Serializer\Annotation as JmsSerializer;

/**
 *
 */
trait TimeStampable
{
    /**
     * @JmsSerializer\Type("DateTimeImmutable")
     *
     * @var DateTimeImmutable
     */
    private $createdAt;

    /**
     * @JmsSerializer\Type("DateTime")
     *
     * @var DateTime|null
     */
    private $updatedAt;

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable $createdAt
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return void
     */
    private function initializeTimeStampable(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }
}
