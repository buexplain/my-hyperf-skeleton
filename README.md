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

## 进程管理

**优雅停止信号：** 
`kill -15 $(cat runtime/hyperf.pid)`

**强杀信号：** 
`kill -9 -$(cat runtime/hyperf.pid)`

**更好的管理方式：**

```bash
vi /etc/systemd/system/skeleton.service
```

写入：

```bash
[Unit]
Description=Skeleton Http Server
After=network.target
After=syslog.target

[Service]
Type=simple
LimitNOFILE=65535
ExecStart=/opt/php/bin/php /opt/skeleton/bin/hyperf.php start
ExecStop=/bin/kill -15 $MAINPID
Restart=always

[Install]
WantedBy=multi-user.target graphical.target
```

修改文件权限：
```bash
chmod 644  /etc/systemd/system/skeleton.service
```

注意：`skeleton`要改为你项目的名称，`ExecStart`的路径要改为绝对路径。

使用方式：
```bash
systemctl start skeleton.service
systemctl status skeleton.service
systemctl stop skeleton.service
```

## License
[Apache-2.0](http://www.apache.org/licenses/LICENSE-2.0.html)