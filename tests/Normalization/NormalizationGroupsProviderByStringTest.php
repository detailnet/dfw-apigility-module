<?php

namespace DetailTest\Apigility\Normalization;

use Detail\Apigility\Normalization\NormalizationGroupsProviderByString;
use PHPUnit\Framework\TestCase;

class NormalizationGroupsProviderByStringTest extends TestCase
{
    public function provideGroupsAsString(): array
    {
        return [
            [
                'foo',
                ['Default', 'foo'],
            ],
            [
                'foo,bar',
                ['Default', 'foo', 'bar'],
            ],
            [ // Sanitation test
                'foo , bar, foo-bar ,foo.bar,foo_bar,4foo.2bar',
                ['Default', 'foo', 'bar', 'foobar', 'foo.bar', 'foo_bar', '4foo.2bar'],
            ],
            [ // CamelCase test (still not supported)
                'FooBar,fooBar',
                ['Default', 'foobar', 'foobar'],
            ],

        ];
    }

    /**
     * @dataProvider provideGroupsAsString
     */
    public function testProperGroupNameForEntity(string $groups, array $expectedGroups): void
    {
        $provider = new NormalizationGroupsProviderByString(explode(',', $groups));

        $this->assertEquals($expectedGroups, $provider->getGroups(null));
    }
}
