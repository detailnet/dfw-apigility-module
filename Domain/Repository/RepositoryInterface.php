<?php

namespace Application\Core\Domain\Repository;

interface RepositoryInterface
{
    public function create(array $data);

    public function find($id);

    public function findAll();

    /**
     * Find entities by a set of criteria.
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return \Application\Core\Domain\Collection\CollectionInterface
     *
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);
//
//    /**
//     * Find a single entity by a set of criteria.
//     *
//     * @param array $criteria
//     * @param array|null $orderBy
//     * @return object|null The entity instance or NULL if the entity can not be found.
//     */
//    public function findOneBy(array $criteria, array $orderBy = null);
//
//    public function removeAll();
//
    public function size();
//
//    public function beginTransaction();
//
//    public function commitTransaction();
//
//    public function rollbackTransaction();
}
