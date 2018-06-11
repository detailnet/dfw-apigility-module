<?php

namespace DetailTest\Apigility\Options;

use Detail\Apigility\Options\ModuleOptions;
use Detail\Apigility\Options\Normalization\NormalizationOptions;

class ModuleOptionsTest extends OptionsTestCase
{
    /**
     * @var ModuleOptions
     */
    protected $options;

    protected function setUp()
    {
        $this->options = $this->getOptions(
            ModuleOptions::CLASS,
            [
                'getNormalization',
                'setNormalization',
            ]
        );
    }

    public function testNormalizationCanBeSet()
    {
        $this->assertNull($this->options->getNormalization());

        $this->options->setNormalization([]);

        $normalization = $this->options->getNormalization();

        $this->assertInstanceOf(NormalizationOptions::CLASS, $normalization);
    }
}
