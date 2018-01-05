<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory;

use BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\ChainMemberInterface as MetaDataMember;
use BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ChainMemberInterface as TypeMember;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ProcessorFactoryBuilderTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testRegistrationOnConstruction(): void
    {
        $metaDataMember = $this->createMock(MetaDataMember::class);
        $typeMember = $this->createMock(TypeMember::class);

        $decoratedProcFactory = $this->createMock(ProcessorFactoryInterface::class);
        $decoratedProcFactory->expects(self::once())
            ->method('addMetaDataChainMember')
            ->with($metaDataMember);
        $decoratedProcFactory->expects(self::once())
            ->method('addTypeChainMember')
            ->with($typeMember);

        $newProcFactory = ProcessorFactoryBuilder::build($decoratedProcFactory, [$metaDataMember], [$typeMember]);

        self::assertInstanceOf(ProcessorFactoryInterface::class, $newProcFactory);
        self::assertSame($decoratedProcFactory, $newProcFactory);
    }
}
