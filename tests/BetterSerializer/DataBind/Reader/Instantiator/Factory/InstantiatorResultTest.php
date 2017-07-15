<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\Reader\Instantiator\InstantiatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class InstantiatorResultTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Factory
 */
class InstantiatorResultTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $instantiator = $this->getMockBuilder(InstantiatorInterface::class)->getMock();
        $metaData = $this->getMockBuilder(MetaDataInterface::class)->getMock();

        /* @var $instantiator InstantiatorInterface */
        /* @var $metaData MetaDataInterface */
        $result = new InstantiatorResult($instantiator, $metaData);

        self::assertSame($instantiator, $result->getInstantiator());
        self::assertSame($metaData, $result->getProcessedMetaData());
    }
}
