<?php

namespace Application\Core\Commanding\Command;

abstract class ListCommand implements
    CommandInterface
{
    /**
     * @var array
     */
    protected $criteria;

    /**
     * @var array
     */
    protected $orderBy;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    public static function fromArray(array $params)
    {
        return new static(
            isset($params['criteria']) ? $params['criteria'] : array(),
            isset($params['orderBy']) ? $params['orderBy'] : array(),
            isset($params['limit']) ? $params['limit'] : null,
            isset($params['offset']) ? $params['offset'] : null
        );
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     */
    public function __construct(array $criteria = array(), array $orderBy = array(), $limit = null, $offset = null)
    {
        $this->criteria = $criteria;
        $this->orderBy = $orderBy;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return array
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param array $criteria
     */
    public function setCriteria(array $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * @return array
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param array $orderBy
     */
    public function setOrderBy(array $orderBy)
    {
        $this->orderBy = $orderBy;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }
}
