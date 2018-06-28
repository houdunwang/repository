# Repository 

Repository 模式主要思想是建立一个数据操作代理层，把controller里的数据操作剥离出来。

Repository 模式是架构模式，在设计架构时，才有参考价值。应用 Repository 模式所带来的好处，远高于实现这个模式所增加的代码。只要项目分层，都应当使用这个模式。

这样做有几个好处：

- 把数据处理逻辑分离使得代码更容易维护
- 数据处理逻辑和业务逻辑分离，可以对这两个代码分别进行测试
- 减少代码重复
- 降低代码出错的几率
- 让controller代码的可读性大大提高

[TOC]

## 定义

在 app/Repositorys/Models 目录中定义仓库类文件如下：
```
namespace App\Repositorys\Models;
use App\User;
use houdunwang\repository\Repository;
class UserRepository extends Repository
{
    function model()
    {
        return User::class;
    }
}
```

### 默认方法

#### 查找所有记录
```
public function all($columns = ['*'])
```

#### 分页查询
```
public function paginate($page = 15, $columns = ['*'])
```

#### 新增记录
```
public function create(array $data)
```

#### 更新记录
```
public function update(array $data, $id)
```

#### 根据主键删除记录
```
public function delete($id)
```

#### 按主键查询记录
```
public function find($id, $columns = ['*'])
```

#### 按指定字段值查询
```
public function findBy($field, $value, $columns = ['*'])
```

## 控制器中使用
```
namespace App\Http\Controllers;

use namespace App\Repositorys\Models\UserRepository;
class Entry extends Controller
{
    public function __construct(UserRepository $userRepository)
    {
        dump($userRepository->all());
    }
...
}
```



# 查询规则

[TOC]

## 定义
扩展查询规则是为 repository 模式中添加扩展查询选项，比如设置查询的记录条数等。
在 system/repository/rule/user 目录中定义查询规则类文件如下：
```
namespace system\repository\rule\user;
use houdunwang\model\repository\Repository;
use houdunwang\model\repository\Rule;
class UserLimitRule extends Rule
{
    protected $limit;
    public function __construct($limit = 10)
    {
        $this->limit = $limit;
    }
    public function apply($model, Repository $repository)
    {
        return $model->limit($this->limit);
    }
}
```
## 默认方法
#### 重新使用规则
```
public function resetRule();
```

#### 不执行任何规则
```
 public function skipRule($status = true);
```

#### 获取所有规则
```
public function getRule();
```

#### 获取指定的规则
```
public function getByRule(Rule $Rule);
```

#### 添加规则
```
public function pushRule(Rule $Rule);
```

#### 使用集合中的所有规则
```
public function applyRule();
```

## 控制器中使用
```
$userRepository->pushRule(new UserLimitRule())->all();
```