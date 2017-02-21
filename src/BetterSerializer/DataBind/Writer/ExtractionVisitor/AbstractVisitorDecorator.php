<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Writer\ExtractionVisitor;

/**
 * Class AbstractVisitorDecorator
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\ExtractionVisitor
 */
abstract class AbstractVisitorDecorator implements VisitorInterface
{

    /**
     * @var VisitorInterface
     */
    private $decorated;

    /**
     * AbstractVisitorDecorator constructor.
     * @param VisitorInterface $decorated
     */
    public function __construct(VisitorInterface $decorated)
    {
        $this->decorated = $decorated;
    }
}
