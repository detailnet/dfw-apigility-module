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
        return array(
            array(
                'Foo',
                array('Default', 'foo'),
            ),
            array(
                'FooBar',
                array('Default', 'foo_bar'),
            ),
            array(
                'Foo8Bar',
                array('Default', 'foo8_bar'),
            ),
            array(
                'Foo_Bar',
                array('Default', 'foo_bar'),
            ),
            array(
                'Foo__Bar',
                array('Default', 'foo_bar'),
            ),
            array(
                'Foo_bar',
                array('Default', 'foo_bar'),
            ),
            array(
                'FOOBar',
                array('Default', 'foo_bar'),
            ),
            array(
                'fooBARfoo',
                array('Default', 'foo_ba_rfoo'),
            ),
        );
    }

    /**
     * @param string $entityName
     * @param array $expectedGroups
     * @dataProvider provideEntities
     */
    public function testProperGroupNameForEntity($entityName, array $expectedGroups)
    {
        $provider = $this->getMockBuilder(NormalizationGroupsProvider::CLASS)
            ->setMethods(array('getEntityName'))
            ->getMock();
        
        $provider
            ->expects($this->any())
            ->method('getEntityName')
            ->will($this->returnValue($entityName));

        /** @var NormalizationGroupsProvider $provider */

        $this->assertEquals($expectedGroups, $provider->getGroups(new HalEntity(new \stdClass())));
    }
}
