<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\Dto;

/**
 * @Serializer\RootName(value="car")
 */
interface CarInterface
{
    /**
     * @Serializer'Property(name="titlex")
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return string
     */
    public function getColor(): string;

    /**
     * @return Radio
     */
    public function getRadio(): Radio;

    /**
     * @return array
     */
    public function getDoors(): array;
}
