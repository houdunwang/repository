<?php namespace Houdunwang\Repository;

/**
 * Repository 规则接口类
 * Interface BaseRepositoryInterface
 *
 * @package Houdunwang\Repository
 */
interface BaseRepositoryInterface
{
    //查找单条
    public function find($id);

    //获取所有
    public function all();

    //分页数据
    public function paginate($page = 15);

    //新增模型
    public function create($data);

    //更改模型
    public function update($model, $data);

    //删除模型
    public function destroy($model);

    //根据属性条件查询
    public function findByAttributes(array $attributes);

    //多个主键数据
    public function findByMany(array $ids);

    //根据属性条件获取多条
    public function getByAttributes(array $attributes, $orderBy = null, $sortOrder = 'asc');

    //清除缓存（缓存仓库有效）
    public function clearCache();
}
