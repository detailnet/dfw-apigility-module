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
//                'getNormalizer',
//                'setNormalizer',
            )
        );
    }

//    public function testNormalizerCanBeSet()
//    {
//        $normalizer = 'Some\Normalizer\Class';
//
//        $this->assertNull($this->options->getNormalizer());
//
//        $this->options->setNormalizer($normalizer);
//
//        $this->assertEquals($normalizer, $this->options->getNormalizer());
//    }
}
