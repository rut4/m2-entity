<?php
namespace Oggetto\Entities\Api;

use Oggetto\Entities\Api\Data\EntityInterface;

interface EntityRepositoryInterface
{
    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    public function save(EntityInterface $entity);

    /**
     * @param int $id
     * @return EntityInterface
     */
    public function getById($id);

    /**
     * @param EntityInterface $entity
     * @return bool
     */
    public function delete(EntityInterface $entity);
}
