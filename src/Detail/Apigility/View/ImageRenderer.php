<?php

namespace Detail\Apigility\View;

use Zend\View\Model\ModelInterface as ViewModelInterface;
use Zend\View\Renderer\RendererInterface;

use Zend\View\Resolver\ResolverInterface;

class ImageRenderer implements
    RendererInterface
{
    /**
     * @param ViewModelInterface|string $nameOrModel
     * @param array|null $values
     * @return string
     */
    public function render($nameOrModel, $values = null)
    {
        if (!$nameOrModel instanceof ImageModel) {
            /** @todo Throw exception */
        }

        $payload = $this->renderImage($nameOrModel);

        if ($payload === null) {
            /** @todo Throw exception */
        }

        return $payload;
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
     * @param ResolverInterface $resolver
     * @return RendererInterface
     */
    public function setResolver(ResolverInterface $resolver)
    {
        // Just ignore (we don't need a resolver)
        return $this;
    }

    /**
     * @param ModelInterface $model
     * @return string|null
     */
    protected function renderImage(ModelInterface $model)
    {
        if ($model->isEntity()) {
            /** @var \ZF\Hal\Entity $halEntity */
            $halEntity = $model->getPayload();
            // zf-hal:1.4.0 introduced a getter for entities and deprecated the access through the public property
            if (method_exists($halEntity, 'getEntity')) {
                $entity = $halEntity->getEntity();
            } else {
                $entity = $halEntity->entity;
            }

            /** @todo How can we fetch the image from the URL? */
        }
    }
}
