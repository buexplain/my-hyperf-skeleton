# JSON接口环境说明

环境|域名
:---|:---
测试|http://domain:port
预生产|http://domain:port
生产|http://domain:port

# 接口说明

## 响应Header
key|value
:---|:---
Content-Type|application/json

## 响应body

参数|类型|说明
:---|:---|:---
code|int|响应码，0：成功，> 0：失败
message|string|响应描述
data|mixed|响应数据

### code说明

code|说明
:--|:--
0|请求成功
396|请求未授权
397|请求地址错误
398|客户端参数错误
399|未找到相关信息
1 ~ 400|客户端错误
\>= 500|服务端错误
501|第三方接口错误

> NOTE:
>
> 此处定义的所有code为公共的有名code，业务层面的code，具体接口具体定义，符合以下规则：
> 1. `1 ~ 400` 为客户端错误，客户端需要处理
> 2. `>= 500` 为服务端端错误，服务端需要处理
> 3. 各个接口自定义的code，互不冲突

### 示例
```json
{
    "code":0,
    "message":"成功",
    "data":{}
}
```