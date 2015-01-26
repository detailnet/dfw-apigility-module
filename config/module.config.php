<?php

return array(
    'service_manager' => array(
        'abstract_factories' => array(
        ),
        'aliases' => array(
        ),
        'invokables' => array(
        ),
        'factories' => array(
            'Detail\Apigility\Options\ModuleOptions' => 'Detail\Apigility\Factory\Options\ModuleOptionsFactory',
            'Detail\Apigility\View\JsonRenderer'     => 'Detail\Apigility\Factory\View\JsonRendererFactory',
            'Detail\Apigility\View\JsonStrategy'     => 'Detail\Apigility\Factory\View\JsonStrategyFactory',
        ),
        'initializers' => array(
            'Detail\Apigility\Rest\Resource\ResourceInitializer',
        ),
        'shared' => array(
        ),
    ),
    'controllers' => array(
        'initializers' => array(
        ),
    ),
//    'zf-hal' => array(
//        'renderer' => array(
//            'default_hydrator' => 'Detail\Normalization\ZendHydrator\NormalizerBasedHydrator',
//        ),
//    ),
    'detail_apigility' => array(
        'normalizer' => 'Detail\Normalization\Normalizer\JMSSerializerBasedNormalizer',
        'request_command_map' => array(),
    ),
);
