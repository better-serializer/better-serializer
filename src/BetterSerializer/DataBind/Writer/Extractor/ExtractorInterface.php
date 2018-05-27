<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Writer\Extractor;

/**
 *
 */
interface ExtractorInterface
{

    /**
     * @param object|null $object
     * @return mixed
     */
    public function extract(?object $object);
}
