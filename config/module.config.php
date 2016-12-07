<?php

return array(
    'service_manager' => array(
        'abstract_factories' => array(
        ),
        'aliases' => array(
        ),
        'invokables' => array(
            'Detail\Apigility\JMSSerializer\Handler\HalCollectionHandler' => 'Detail\Apigility\JMSSerializer\Handler\HalCollectionHandler',
            'Detail\Apigility\Normalization\NormalizationGroupsProvider'  => 'Detail\Apigility\Normalization\NormalizationGroupsProvider',
        ),
        'factories' => array(
            'Detail\Apigility\Hydrator\NormalizerBasedHydrationListener' => 'Detail\Apigility\Factory\Hydrator\NormalizerBasedHydrationListenerFactory',
            'Detail\Apigility\Hydrator\NormalizerBasedHydrator'          => 'Detail\Apigility\Factory\Hydrator\NormalizerBasedHydratorFactory',
            'Detail\Apigility\Options\ModuleOptions'                     => 'Detail\Apigility\Factory\Options\ModuleOptionsFactory',
            'Detail\Apigility\View\JsonRenderer'                         => 'Detail\Apigility\Factory\View\JsonRendererFactory',
            'Detail\Apigility\View\JsonStrategy'                         => 'Detail\Apigility\Factory\View\JsonStrategyFactory',
            'Detail\Apigility\View\XmlRenderer'                         => 'Detail\Apigility\Factory\View\XmlRendererFactory',
            'Detail\Apigility\View\XmlStrategy'                         => 'Detail\Apigility\Factory\View\XmlStrategyFactory',
        ),
        'delegators' => array(
            'ZF\ContentValidation\ContentValidationListener' => array(
                'Detail\Apigility\Factory\ContentValidation\ContentValidationListenerDelegatorFactory',
            ),
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
    'jms_serializer' => array(
        'handlers' => array(
            'subscribers' => array(
                'Detail\Apigility\JMSSerializer\Handler\HalCollectionHandler',
            ),
        ),
    ),
    'zf-hal' => array(
        'renderer' => array(
            'default_hydrator' => 'Detail\Apigility\Hydrator\NormalizerBasedHydrator',
        ),
    ),
    'detail_apigility' => array(
        'normalization' => array(
            'normalizer' => 'Detail\Normalization\Normalizer\JMSSerializerBasedNormalizer',
            'groups_provider' => 'Detail\Apigility\Normalization\NormalizationGroupsProvider',
        ),
        'hal' => array(
            'listeners' => array(
                'Detail\Apigility\Hydrator\NormalizerBasedHydrationListener',
            ),
        ),
        'request_command_map' => array(),
    ),
);
