<?php

namespace Detail\Apigility\View;

use Zend\View\Model\ViewModel;

use ZF\Hal\Collection as HalCollection;
use ZF\Hal\Entity as HalEntity;

class XmlModel extends ViewModel implements
    ModelInterface
{
    /**
     * XML is usually terminal.
     *
     * @var boolean
     */
    protected $terminate = true;

    /**
     * Does the payload represent a HAL collection?
     *
     * @return bool
     */
    public function isCollection()
    {
        $payload = $this->getPayload();
        return ($payload instanceof HalCollection);
    }

    /**
     * Does the payload represent a HAL item?
     *
     * Deprecated; please use isEntity().
     *
     * @deprecated
     * @return boolean
     */
    public function isResource()
    {
        trigger_error(sprintf('%s is deprecated; please use %s::isEntity', __METHOD__, __CLASS__), E_USER_DEPRECATED);
        return self::isEntity();
    }

    /**
     * Does the payload represent a HAL entity?
     *
     * @return boolean
     */
    public function isEntity()
    {
        $payload = $this->getPayload();
        return ($payload instanceof HalEntity);
    }

    /**
     * Set the payload for the response.
     *
     * This is the value to represent in the response.
     *
     * @param mixed $payload
     * @return self
     */
    public function setPayload($payload)
    {
        $this->setVariable('payload', $payload);
        return $this;
    }

    /**
     * Retrieve the payload for the response.
     *
     * @return mixed
     */
    public function getPayload()
    {
        return $this->getVariable('payload');
    }

    /**
     * Override setTerminal().
     *
     * Does nothing; does not allow re-setting "terminate" flag.
     *
     * @param boolean $flag
     * @return self
     */
    public function setTerminal($flag = true)
    {
        return $this;
    }
}
