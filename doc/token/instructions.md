# token 使用说明

## token的时效性
token是有时效的，客户端需要根据[服务端返回的token过期时间与服务器当前时间](./in.md)，对token的过期进行监控。
当token即将过期时，应调用[刷新token接口](./refresh.md)，重新获取token。

## token的使用方式
如果某个接口需要客户端传递token，那么客户端应该在`http`请求的`header`头部中传递字段`Authorization`。
示例：`Authorization: 6hxMWMW1sCBL65GXdLRsGkruwV9Z6p9i4`。
