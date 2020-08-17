# 销毁token

## 请求说明

**接口类型** [JSON](./../instructions/json.md)

**请求URL** `/token/out`

**请求方式** `DELETE`

**请求Header** `Authorization: token字符串`

## 返回说明

### code 说明

code|说明
:---|:---
0|成功
\>0|失败

### code == 0

返回示例

```
{
    "code":0,
    "message":"成功",
    "data":{}
}
```