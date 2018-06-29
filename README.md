# Repository 

Repository 模式主要思想是建立一个数据操作代理层，把controller里的数据操作剥离出来。

Repository 模式是架构模式，在设计架构时，才有参考价值。应用 Repository 模式所带来的好处，远高于实现这个模式所增加的代码。只要项目分层，都应当使用这个模式。

这样做有几个好处：

- 把数据处理逻辑分离使得代码更容易维护
- 数据处理逻辑和业务逻辑分离，可以对这两个代码分别进行测试
- 减少代码重复
- 降低代码出错的几率
- 让controller代码的可读性大大提高

## 基础接口方法
```
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
```

## 模型仓库
模型仓库是对数据模型的管理中间件。
#### 声明仓库
```
namespace App\Repositories\Eloquent;

use Houdunwang\Repository\EloquentBaseRepository;

class ConfigRepository extends EloquentBaseRepository
{

}
```

#### 使用仓库
```
namespace App\Http\Controllers;

use App\Models\Config;
use App\Repositories\Eloquent\ConfigRepository;

class HomeController extends Controller
{
    public function __construct(Config $config)
    {
        $repository = new ConfigRepository($config);
        dd($repository->find(1));
    }
...
```
## 缓存仓库
缓存仓库使用与模型仓库区别不大，只是加入了缓存中间层对数据进行缓存处理。
当模型数据发生变化时自动更新缓存。

#### 声明仓库

```
namespace App\Repositories\Cache;
use Houdunwang\Repository\CacheBaseRepository;
class ConfigRepository extends CacheBaseRepository
{

}
```

#### 使用仓库

```
namespace App\Http\Controllers;
use App\Models\Config;
use App\Repositories\Eloquent\ConfigRepository;
class HomeController extends Controller
{
    public function __construct(Config $config)
    {
        $repository = new ConfigRepository($config);
        dd($repository->find(1));
    }
...
```



