<?php namespace Houdunwang\Repository;

use Illuminate\Cache\Repository;
use \Illuminate\Config\Repository as ConfigRepository;

abstract class CacheBaseRepository implements BaseRepositoryInterface
{
    /**
     * 模型仓库
     *
     * @var
     */
    protected $repository;

    /**
     * 缓存处理仓库
     *
     * @var \Illuminate\Foundation\Application|mixed
     */
    protected $cache;

    /**
     * 缓存标记
     *
     * @var
     */
    protected $entityName;

    /**
     * @var string The application locale
     */
    protected $locale;

    /**
     * 缓存时间
     *
     * @var mixed
     */
    protected $cacheTime;

    public function __construct()
    {
        $this->cache     = app(Repository::class);
        $this->cacheTime = app(ConfigRepository::class)->get('cache.time', 60);
        $this->locale    = app()->getLocale();
    }

    /**
     * 根据主键获取一条
     *
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->remember(function () use ($id) {
            return $this->repository->find($id);
        });
    }

    /**
     * 获取所有
     *
     * @return mixed
     */
    public function all()
    {
        return $this->remember(function () {
            return $this->repository->all();
        });
    }

    /**
     * 分页获取
     *
     * @param int $perPage
     *
     * @return mixed
     */
    public function paginate($perPage = 15)
    {
        return $this->remember(function () use ($perPage) {
            return $this->repository->paginate($perPage);
        });
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
        $this->cache->tags($this->entityName)->flush();

        return $this->repository->create($data);
    }

    /**
     * 更新
     *
     * @param $model 模型实例
     * @param $data  更新内容
     *
     * @return mixed
     */
    public function update($model, $data)
    {
        $this->cache->tags($this->entityName)->flush();

        return $this->repository->update($model, $data);
    }

    /**
     * 删除
     *
     * @param $model 模型实例
     *
     * @return mixed
     */
    public function destroy($model)
    {
        $this->cache->tags($this->entityName)->flush();

        return $this->repository->destroy($model);
    }

    /**
     * 根据属性获取
     *
     * @param array $attributes 查询属性条件
     *
     * @return mixed
     */
    public function findByAttributes(array $attributes)
    {
        return $this->remember(function () use ($attributes) {
            return $this->repository->findByAttributes($attributes);
        });
    }

    /**
     * 根据属性获取多条
     *
     * @param array  $attributes 属性
     * @param null   $orderBy    排序字段
     * @param string $sortOrder  排序方式
     *
     * @return mixed
     */
    public function getByAttributes(array $attributes, $orderBy = null, $sortOrder = 'asc')
    {
        return $this->remember(function () use ($attributes, $orderBy, $sortOrder) {
            return $this->repository->getByAttributes($attributes, $orderBy, $sortOrder);
        });
    }

    /**
     * 根据主键获取多条
     *
     * @param array $ids 主键列表
     *
     * @inheritdoc
     */
    public function findByMany(array $ids)
    {
        return $this->remember(function () use ($ids) {
            return $this->repository->findByMany($ids);
        });
    }

    /**
     * 清除缓存
     */
    public function clearCache()
    {
        $store = $this->cache;

        if (method_exists($this->cache->getStore(), 'tags')) {
            $store = $store->tags($this->entityName);
        }

        return $store->flush();
    }

    /**
     * 更新缓存
     *
     * @param \Closure $callback
     * @param null     $key
     *
     * @return mixed
     */
    protected function remember(\Closure $callback, $key = null)
    {
        $cacheKey = $this->makeCacheKey($key);

        $store = $this->cache;

        if (method_exists($this->cache->getStore(), 'tags')) {
            $store = $store->tags([$this->entityName, 'global']);
        }

        return $store->remember($cacheKey, $this->cacheTime, $callback);
    }

    /**
     * 获取缓存键名
     *
     * @param null $key
     *
     * @return string
     */
    private function makeCacheKey($key = null): string
    {
        if ($key !== null) {
            return $key;
        }
        $cacheKey  = $this->getBaseKey();
        $backtrace = debug_backtrace()[2];

        return sprintf("$cacheKey %s %s", $backtrace['function'], \serialize($backtrace['args']));
    }

    /**
     * 缓存标识位
     *
     * @return string
     */
    protected function getBaseKey(): string
    {
        return sprintf(
            'hdcms -locale:%s -entity:%s',
            $this->locale,
            $this->entityName
        );
    }
}
