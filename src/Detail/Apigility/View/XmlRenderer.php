<?php

namespace Detail\Apigility\View;

use Zend\View\Model\ModelInterface;
use Zend\View\Renderer\RendererInterface;

use Detail\Normalization\Normalizer\NormalizerInterface;
use Detail\Normalization\Normalizer\Service\NormalizerAwareInterface;

use Detail\Apigility\Normalization\NormalizationGroupsProviderAwareInterface;
use Detail\Apigility\Normalization\NormalizationGroupsProviderInterface;
use Zend\View\Resolver\ResolverInterface;

class XmlRenderer implements
    NormalizerAwareInterface,
    NormalizationGroupsProviderAwareInterface,
    RendererInterface
{
    use NormalizerBasedRendererTrait;

    /**
     * @param NormalizerInterface $normalizer
     * @param NormalizationGroupsProviderInterface $normalizationGroupsProvider
     */
    public function __construct(
        NormalizerInterface $normalizer,
        NormalizationGroupsProviderInterface $normalizationGroupsProvider = null
    ) {
        $this->setNormalizer($normalizer);

        if ($normalizationGroupsProvider !== null) {
            $this->setNormalizationGroupsProvider($normalizationGroupsProvider);
        }
    }

    /**
     * @param ModelInterface|string $nameOrModel
     * @param array|null| $values
     * @return string
     */
    public function render($nameOrModel, $values = null)
    {
        if (!$nameOrModel instanceof XmlModel) {
            /** @todo Throw exception */
        }

        $payload = $this->normalizeEntityOrCollection($nameOrModel);

        if ($payload === null) {
            /** @todo Throw exception */
        }

        /** @todo We have PHP arrays now, how to get to XML? Should the normalizer support serialization to XML directly */
        return var_export($payload, true); // This does NOT return XML as it should!
    }

    /**
     * Return the template engine object, if any
     *
     * If using a third-party template engine, such as Smarty, patTemplate,
     * phplib, etc, return the template engine object. Useful for calling
     * methods on these objects, such as for setting filters, modifiers, etc.
     *
     * @return mixed
     */
    public function getEngine()
    {
        return $this;
    }

    /**
     * Set the resolver used to map a template name to a resource the renderer may consume.
     *
     * @param  ResolverInterface $resolver
     * @return RendererInterface
     */
    public function setResolver(ResolverInterface $resolver)
    {
        // Just ignore (we don't need a resolver)
        return $this;
    }
}
