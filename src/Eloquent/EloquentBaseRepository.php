<?php namespace Houdunwang\Repository;

/**
 * 模型仓库基础类
 * Class EloquentBaseRepository
 *
 * @package Modules\Core\Repositories\Eloquent
 */
abstract class EloquentBaseRepository implements BaseRepositoryInterface
{
    /**
     * 模型
     *
     * @var
     */
    protected $model;

    /**
     * 构造函数
     * EloquentBaseRepository constructor.
     *
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * 查找单条
     *
     * @param $id 主键
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * 所有数据
     *
     * @return mixed
     */
    public function all()
    {
        return $this->model->orderBy('created_at', 'DESC')->get();
    }

    /**
     * 分页数据
     *
     * @param int $perPage
     *
     * @return mixed
     */
    public function paginate($perPage = 15)
    {
        return $this->model->orderBy('created_at', 'DESC')->paginate($perPage);
    }

    /**
     * 创建
     *
     * @param $data
     *
     * @return mixed
     */
    public function create($data)
    {
        return $this->model->create($data);
    }

    /**
     * 更新
     *
     * @param $model
     * @param $data
     *
     * @return mixed
     */
    public function update($model, $data)
    {
        $model->update($data);

        return $model;
    }

    /**
     * 删除
     *
     * @param $model
     *
     * @return mixed
     */
    public function destroy($model)
    {
        return $model->delete();
    }

    /**
     * 根据属性获取
     *
     * @param array $attributes 查询属性条件
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function findByAttributes(array $attributes)
    {
        $query = $this->buildQueryByAttributes($attributes);

        return $query->first();
    }

    /**
     * 根据属性获取多条
     *
     * @param array  $attributes 属性
     * @param null   $orderBy    排序字段
     * @param string $sortOrder  排序方式
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getByAttributes(array $attributes, $orderBy = null, $sortOrder = 'asc')
    {
        $query = $this->buildQueryByAttributes($attributes, $orderBy, $sortOrder);

        return $query->get();
    }

    /**
     * 组合查询条件
     *
     * @param array  $attributes 查询属性条件
     * @param null   $orderBy    排序字段
     * @param string $sortOrder  排序方式
     *
     * @return mixed
     */
    private function buildQueryByAttributes(array $attributes, $orderBy = null, $sortOrder = 'asc')
    {
        $query = $this->model->query();

        foreach ($attributes as $field => $value) {
            $query = $query->where($field, $value);
        }

        if (null !== $orderBy) {
            $query->orderBy($orderBy, $sortOrder);
        }

        return $query;
    }

    /**
     * 根据主键获取多条
     *
     * @param array $ids 主键列表
     *
     * @return mixed
     */
    public function findByMany(array $ids)
    {
        $query = $this->model->query();

        return $query->whereIn("id", $ids)->get();
    }

    /**
     * 清除缓存
     *
     * @inheritdoc
     */
    public function clearCache()
    {
        return true;
    }
}
