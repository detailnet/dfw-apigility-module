<?php

namespace DetailTest\Apigility\Options;

class ModuleOptionsTest extends OptionsTestCase
{
    /**
     * @var \Detail\Apigility\Options\ModuleOptions
     */
    protected $options;

    protected function setUp()
    {
        $this->options = $this->getOptions(
            'Detail\Apigility\Options\ModuleOptions',
            array(
                'getNormalization',
                'setNormalization',
            )
        );
    }

    public function testNormalizationCanBeSet()
    {
        $this->assertNull($this->options->getNormalization());

        $this->options->setNormalization(array());

        $normalization = $this->options->getNormalization();

        $this->assertInstanceOf('Detail\Apigility\Options\Normalization\NormalizationOptions', $normalization);
    }
}
