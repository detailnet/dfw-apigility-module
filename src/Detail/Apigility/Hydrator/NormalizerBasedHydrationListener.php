<?php

namespace Detail\Apigility\Hydrator;

use Zend\EventManager\EventInterface;

use ZF\Hal\Collection as HalCollection;
use ZF\Hal\Entity as HalEntity;

use Detail\Apigility\Hal\BaseRenderListener;
use Detail\Apigility\Normalization\NormalizationGroupsProviderAwareInterface;
use Detail\Apigility\Normalization\NormalizationGroupsProviderAwareTrait;
use Detail\Apigility\Normalization\NormalizationGroupsProviderInterface;

class NormalizerBasedHydrationListener extends BaseRenderListener implements
    NormalizationGroupsProviderAwareInterface
{
    use NormalizationGroupsProviderAwareTrait;

    /**
     * @var NormalizerBasedHydrator
     */
    protected $hydrator;

    /**
     * @param NormalizerBasedHydrator $hydrator
     * @param NormalizationGroupsProviderInterface $groupsProvider
     */
    public function __construct(
        NormalizerBasedHydrator $hydrator,
        NormalizationGroupsProviderInterface $groupsProvider = null
    ) {
        $this->setHydrator($hydrator);

        if ($groupsProvider !== null) {
            $this->setNormalizationGroupsProvider($groupsProvider);
        }
    }

    /**
     * @return NormalizerBasedHydrator
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     * @param NormalizerBasedHydrator $hydrator
     */
    public function setHydrator(NormalizerBasedHydrator $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    /**
     * Listener for the "renderCollection" event.
     *
     * @param EventInterface $event
     */
    public function onRenderCollection(EventInterface $event)
    {
        /** @var HalCollection $halCollection */
        $halCollection = $event->getParam('collection');

        $this->applyDefaultGroups($halCollection);
    }

    /**
     * Listener for the "renderCollection.entity" event
     *
     * @param EventInterface $event
     * @return void
     */
    public function onRenderCollectionEntity(EventInterface $event)
    {
        // Nothing to do
    }

    /**
     * Listener for the "renderEntity" event
     *
     * @param EventInterface $event
     */
    public function onRenderEntity(EventInterface $event)
    {
        /** @var HalEntity $halEntity */
        $halEntity = $event->getParam('entity');

        $this->applyDefaultGroups($halEntity);
    }

    /**
     * @param HalEntity|HalCollection $collectionOrEntity
     * @param boolean $force
     */
    protected function applyDefaultGroups($collectionOrEntity, $force = false)
    {
        $groupsProvider = $this->getNormalizationGroupsProvider();
        $hydrator = $this->getHydrator();

        if ($groupsProvider === null
            || ($hydrator->hasDefaultGroups() && $force !== true)
        ) {
            return;
        }

        $groups = $groupsProvider->getGroups($collectionOrEntity);

        $hydrator->setDefaultGroups($groups);
    }
}
