# 获取token

## 请求说明

**接口类型** [JSON](./../instructions/json.md)

**请求URL** `/token/in`

**请求方式** `POST`

**请求参数**

参数|类型|必选|说明
:---|:---|:---|:---
username|string|是|账号
password|string|是|密码

> 本接口有请求限制，连续输入密码错误达到一定次数会限制请求一定分钟

## 返回说明

### code 说明

code|说明
:--|:--
0|成功
398|客户端参数错误
399|账号错误
1|密码错误，您还有 x 次尝试机会
2|连续输入错误密码，锁定 xx 分钟 xx 秒

###  code == 0

data 说明

参数|类型|说明
:---|:---|:---
token|string|token字符串
expire_time|int|token的过期时间
server_time|int|服务器当前时间

返回示例

```
{
    "code":0,
    "message":"成功",
    "data":{
        "token":"E8VvRC6ou14lVYIq4",
        "expire_time":1591695982,
        "server_time":1591609582
    }
}
```

###  code == 399

返回示例

```
{
    "code":399,
    "message":"账号错误",
    "data":{

    }
}
```

返回示例

###  code == 1

```
{
    "code":1,
    "message":"密码错误，您还有 2 次尝试机会",
    "data":{

    }
}
```

###  code == 2

返回示例

```
{
    "code":2,
    "message":"连续输入错误密码，锁定 15 分钟 0 秒",
    "data":{

    }
}
```