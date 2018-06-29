<?php namespace Houdunwang\Repository;

/**
 * Repository 规则接口类
 * Interface BaseRepositoryInterface
 *
 * @package Houdunwang\Repository
 */
interface BaseRepositoryInterface
{
    public function find($id);

    public function all();

    public function paginate($page = 15);

    public function create($data);

    public function update($model, $data);

    public function destroy($model);

    public function findByAttributes(array $attributes);

    public function findByMany(array $ids);

    public function getByAttributes(array $attributes, $orderBy = null, $sortOrder = 'asc');

    public function clearCache();
}
