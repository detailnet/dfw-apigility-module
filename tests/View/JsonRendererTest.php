<?php

namespace DetailTest\Apigility\View;

use Detail\Apigility\Normalization\NormalizationGroupsProvider;
use Detail\Apigility\View\JsonModel;
use Detail\Apigility\View\JsonRenderer;
use PHPUnit\Framework\TestCase;
use ZF\Hal\Entity as HalEntity;

class JsonRendererTest extends TestCase
{
    public function provideEntitiesAndGroups(): \Generator
    {
        // First two tests are same as in \DetailTest\Apigility\Normalization\NormalizationGroupsProviderTest::testProperGroupNameForEntity
        // Needed here to test that the 'normalisation groups' extension does not mess up when no 'normalisation groups' is provided
        yield 'Without own normalisation groups' => [
            'Foo',
            null,
            ['Default', 'foo'],
        ];

        yield 'Without own normalisation groups (entity name conversion to snake case)' => [
            'FooBar',
            null,
            ['Default', 'foo_bar'],
        ];

        yield 'Own normalisation group' => [
            'Foo',
            ['bar'],
            ['Default', 'bar'],
        ];

        yield 'Own normalisation groups' => [
            'Foo',
            ['one', 'two'],
            ['Default', 'one', 'two'],
        ];
    }

    /**
     * @param string $entityName
     * @param array|null $normalizationGroups
     * @param array $expectedGroups
     * @dataProvider provideEntitiesAndGroups
     */
    public function testProperGroupNameForEntity($entityName, ?array $normalizationGroups, array $expectedGroups)
    {
        $provider = $this->getMockBuilder(NormalizationGroupsProvider::CLASS)
            ->setMethods(['getEntityName'])
            ->getMock();

        $provider
            ->expects($this->any())
            ->method('getEntityName')
            ->will($this->returnValue($entityName));

        $renderer = $this->getMockBuilder(JsonRenderer::CLASS)
            ->disableOriginalConstructor()
            ->setMethods(['getNormalizationGroupsProvider', 'normalize'])
            ->getMock();

        $renderer
            ->expects($this->any())
            ->method('getNormalizationGroupsProvider')
            ->will($this->returnValue($provider));

        $renderer
            ->expects($this->atLeastOnce())
            ->method('normalize')
            ->willReturnCallback(
                function ($object, $groups = null) use ($expectedGroups) {
                    $this->assertEquals($expectedGroups, $groups, 'Groups passed to normalization do not match');

                    return ''; // Return type not important, should be :array|string
                }
            );

        /** @var JsonRenderer $renderer */

        $renderer->setNormalizationGroups($normalizationGroups);
        $renderer->render(new JsonModel(['payload' => new HalEntity(new \stdClass())]));
    }
}
