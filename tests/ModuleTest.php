<?php

namespace DetailTest\Apigility;

use PHPUnit\Framework\TestCase;

use Detail\Normalization\Normalizer\JMSSerializerBasedNormalizer;

use Detail\Apigility\Module;

class ModuleTest extends TestCase
{
    /**
     * @var Module
     */
    protected $module;

    protected function setUp()
    {
        $this->module = new Module();
    }

    public function testModuleProvidesConfig()
    {
        $config = $this->module->getConfig();

        $this->assertTrue(is_array($config));
        $this->assertArrayHasKey('detail_apigility', $config);
        $this->assertTrue(is_array($config['detail_apigility']));
        $this->assertArrayHasKey('normalization', $config['detail_apigility']);
        $this->assertTrue(is_array($config['detail_apigility']['normalization']));
        $this->assertArrayHasKey('normalizer', $config['detail_apigility']['normalization']);
        $this->assertEquals(
            JMSSerializerBasedNormalizer::CLASS,
            $config['detail_apigility']['normalization']['normalizer']
        );
    }
}
