<?php

namespace DetailTest\Apigility\Normalization;

use PHPUnit\Framework\TestCase;

use ZF\Hal\Entity as HalEntity;

use Detail\Apigility\Normalization\NormalizationGroupsProvider;

class NormalizationGroupsProviderTest extends TestCase
{
    /**
     * @return array
     */
    public function provideEntities()
    {
        return [
            [
                'Foo',
                ['Default', 'foo'],
            ],
            [
                'FooBar',
                ['Default', 'foo_bar'],
            ],
            [
                'Foo8Bar',
                ['Default', 'foo8_bar'],
            ],
            [
                'Foo_Bar',
                ['Default', 'foo_bar'],
            ],
            [
                'Foo__Bar',
                ['Default', 'foo_bar'],
            ],
            [
                'Foo_bar',
                ['Default', 'foo_bar'],
            ],
            [
                'FOOBar',
                ['Default', 'foo_bar'],
            ],
            [
                'fooBARfoo',
                ['Default', 'foo_ba_rfoo'],
            ],
        ];
    }

    /**
     * @param string $entityName
     * @param array $expectedGroups
     * @dataProvider provideEntities
     */
    public function testProperGroupNameForEntity($entityName, array $expectedGroups)
    {
        $provider = $this->getMockBuilder(NormalizationGroupsProvider::CLASS)
            ->setMethods(['getEntityName'])
            ->getMock();
        
        $provider
            ->expects($this->any())
            ->method('getEntityName')
            ->will($this->returnValue($entityName));

        /** @var NormalizationGroupsProvider $provider */

        $this->assertEquals($expectedGroups, $provider->getGroups(new HalEntity(new \stdClass())));
    }
}
