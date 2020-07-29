# 基于hyperf的开发骨架

本项目是基于 [hyperf](https://github.com/hyperf/hyperf) 的开发骨架，做好了如下工作：
1. 接入钉钉告警
2. 完成token接口
3. 后台管理
4. 文档管理

## 初始化

```bash
git clone https://github.com/buexplain/my-hyperf-skeleton.git
cd my-hyperf-skeleton
composer update
copy .env.example .env
```

`vi .env` 配置好各种参数，执行数据迁移：
 
 ```bash
php bin/hyperf.php migrate:fresh --seed
```

后台地址：`http://ip:port/backend/rbac/sign/index`
> 管理员：admin 123456
>
> 注册用户：test 123456

文档地址：`http://ip:port/public/doc/index.html`

## License
[Apache-2.0](http://www.apache.org/licenses/LICENSE-2.0.html)