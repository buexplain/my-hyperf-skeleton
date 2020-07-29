# 刷新token

## 请求说明

**接口类型** [JSON](./../instructions/json.md)

**请求URL** `/token/refresh`

**请求方式** `POST`

**请求Header** `Authorization: token字符串`

## 返回说明

### code 说明

code|说明
:--|:--
0|成功
\>0|失败

### code == 0

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