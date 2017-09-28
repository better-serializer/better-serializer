<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Writer\Extractor;

/**
 * Class ExtractorInterface
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Extractor
 */
interface ExtractorInterface
{

    /**
     * @param mixed $data
     * @return mixed
     */
    public function extract($data);
}
