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
     * @return array
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }
}
