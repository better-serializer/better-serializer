<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Annotations;

/**
 * Interface AnnotationInterface
 *
 * @package BetterSerializer\DataBind\MetaData\Annotations
 */
interface AnnotationInterface
{

    /**
     * @return string
     */
    public function getAnnotationName(): string;
}
