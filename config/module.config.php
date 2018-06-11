<?php

return [
    'service_manager' => [
        'abstract_factories' => [
        ],
        'aliases' => [
        ],
        'invokables' => [
            'Detail\Apigility\JMSSerializer\Handler\HalCollectionHandler' =>
                'Detail\Apigility\JMSSerializer\Handler\HalCollectionHandler',
            'Detail\Apigility\Normalization\NormalizationGroupsProvider' =>
                'Detail\Apigility\Normalization\NormalizationGroupsProvider',
        ],
        'factories' => [
            // Generic
            'Detail\Apigility\Hydrator\NormalizerBasedHydrationListener' =>
                'Detail\Apigility\Factory\Hydrator\NormalizerBasedHydrationListenerFactory',
            'Detail\Apigility\Hydrator\NormalizerBasedHydrator' =>
                'Detail\Apigility\Factory\Hydrator\NormalizerBasedHydratorFactory',
            'Detail\Apigility\Options\ModuleOptions' => 'Detail\Apigility\Factory\Options\ModuleOptionsFactory',

            // JSON
            'Detail\Apigility\View\JsonRenderer' => 'Detail\Apigility\Factory\View\JsonRendererFactory',
            'Detail\Apigility\View\JsonStrategy' => 'Detail\Apigility\Factory\View\JsonStrategyFactory',

            // XML
            'Detail\Apigility\View\XmlRenderer' => 'Detail\Apigility\Factory\View\XmlRendererFactory',
            'Detail\Apigility\View\XmlStrategy' => 'Detail\Apigility\Factory\View\XmlStrategyFactory',
        ],
        'delegators' => [
            'ZF\ContentValidation\ContentValidationListener' => [
                'Detail\Apigility\Factory\ContentValidation\ContentValidationListenerDelegatorFactory',
            ],
        ],
        'initializers' => [
            'Detail\Apigility\Rest\Resource\ResourceInitializer',
        ],
        'shared' => [
        ],
    ],
    'controllers' => [
        'initializers' => [
        ],
    ],
    'jms_serializer' => [
        'handlers' => [
            'subscribers' => [
                'Detail\Apigility\JMSSerializer\Handler\HalCollectionHandler',
            ],
        ],
    ],
    'zf-hal' => [
        'renderer' => [
            'default_hydrator' => 'Detail\Apigility\Hydrator\NormalizerBasedHydrator',
        ],
    ],
    'detail_apigility' => [
        'normalization' => [
            'normalizer' => 'Detail\Normalization\Normalizer\JMSSerializerBasedNormalizer',
            'groups_provider' => 'Detail\Apigility\Normalization\NormalizationGroupsProvider',
        ],
        'hal' => [
            'listeners' => [
                'Detail\Apigility\Hydrator\NormalizerBasedHydrationListener',
            ],
        ],
        'request_command_map' => [],
    ],
];
