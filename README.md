# OpenHTTP is a PHP HTTP Client library based on GuzzleHttp.

[![GitHub Tag](https://img.shields.io/github/v/tag/nacosvel/open-http)](https://github.com/nacosvel/open-http/tags)
[![Total Downloads](https://img.shields.io/packagist/dt/nacosvel/open-http?style=flat-square)](https://packagist.org/packages/nacosvel/open-http)
[![Packagist Version](https://img.shields.io/packagist/v/nacosvel/open-http)](https://packagist.org/packages/nacosvel/open-http)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/nacosvel/open-http)](https://github.com/nacosvel/open-http)
[![Packagist License](https://img.shields.io/github/license/nacosvel/open-http)](https://github.com/nacosvel/open-http)

```php
<?php

use Nacosvel\OpenHttp\Builder;

$instance = Builder::factory([
    'base_uri' => 'http://httpbin.org/',
], []);

// Send an synchronous request.
$response = $instance->chain('get')->get([
    'query' => [
        'nacosvel' => 'open-http',
    ],
]);
echo $response->getStatusCode();               // 200
echo $response->getHeaderLine('content-type'); // 'application/json; charset=utf8'
echo $response->getBody();                     // '{"args":{"nacosvel":"open-http"},...'

// Send an asynchronous request.
$promise = $instance
    ->chain('get')
    ->getAsync()->then(function ($response) {
        echo 'I completed! ' . $response->getBody();
    })
    ->wait();
```

## 安装

推荐使用 PHP 包管理工具 [Composer](https://getcomposer.org/) 安装：

```bash
composer require nacosvel/open-http
```

## 概览

OpenHTTP is a PHP HTTP Client library based on [Guzzle HTTP Client](http://docs.guzzlephp.org/).

* 支持 [重试](#重试请求) 发送请求
* 支持 [同步](#同步请求) 或 [异步](#异步请求) 发送请求
* [链式实现的 URI Template](#链式-uri-template)
* [自定义扩展](#自定义扩展)
* 项目版本遵循 [语义化版本号](https://semver.org/lang/zh-CN/)
* 推荐使用目前处于 [Active Support](https://www.php.net/supported-versions.php) 阶段的 PHP 8 和 Guzzle 7。

## 文档

### 重试请求

默认情况下没有启用重试机制。如果你想启用请求的重试，可以使用配置中设置 `retry_max` 来实现。

```php
<?php

use GuzzleHttp\Exception\RequestException;
use Nacosvel\OpenHttp\Builder;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

// 提供的“决策者”函数
$retry_decider = function (array $options, int $retries, RequestInterface $request, ResponseInterface $response = null, $exception = null): bool {
    return $retries < $options['retry_max'];
};
// 提供的“延迟”函数
$retry_delay = function (array $options, int $retries): int {
    return 2 ** ($retries - 1) * 1000;
};

$instance = Builder::factory([
    'base_uri'         => 'http://httpbin.org/',
    'retry_max'        => 3,
    'retry_status'     => ['5xx'],
    'retry_exceptions' => [RequestException::class],
    'retry_decider'    => $retry_decider,
    'retry_delay'      => $retry_delay,
], []);

$response = $instance->chain('get')->get(['query' => ['foo' => 'bar']]);
```

重试请求参数说明：

+ 请求重试次数（必填）：`retry_max`
+ 请求重试响应状态码策咯（可选）：`retry_status`
  > 默认：`['5xx']` 表示状态码 500（含）至 599（含）
+ 请求重试异常策咯（可选）：`retry_exceptions`
  > 默认：`[RequestException::class]`
+ 提供的“决策者”函数（可选）：`retry_decider`
  > 默认：`retry_status` 或 `retry_exceptions` 时重试请求
+ 提供的“延迟”函数（可选）：`retry_delay`
  > 默认：`2 ** ($retries - 1) * 1000`

函数说明：

```php
function retry_decider(array $options, int $retries, RequestInterface $request, ResponseInterface $response = null, $exception = null): bool;
```

决策函数指定了什么时候应该重试请求，例如当请求返回 5xx 响应码时或在连接异常时进行重试。

```php
function retry_delay(array $options, int $retries): int;
```

延时函数的主要功能是控制每次重试请求之间的等待时间，从而避免请求被过于频繁地发送，尤其是在处理失败或错误的情况下。

### 同步请求

使用客户端提供的 `get`、`head`、`put`、`post`、`patch` 或 `delete` 方法发送同步请求。

```php
<?php

try {
    $resp = $instance
        ->chain('v3/pay/transactions/native')
        ->post(['json' => [
            'mchid'        => '1900006XXX',
            'out_trade_no' => 'native12177525012014070332333',
            'appid'        => 'wxdace645e0bc2cXXX',
            'description'  => 'Image形象店-深圳腾大-QQ公仔',
            'notify_url'   => 'https://weixin.qq.com/',
            'amount'       => [
                'total'    => 1,
                'currency' => 'CNY',
            ],
        ]]);

    echo $resp->getStatusCode(), PHP_EOL;
    echo $resp->getBody(), PHP_EOL;
} catch (\Exception $e) {
    // 进行错误处理
    echo $e->getMessage(), PHP_EOL;
    if ($e instanceof \GuzzleHttp\Exception\RequestException && $e->hasResponse()) {
        $r = $e->getResponse();
        echo $r->getStatusCode() . ' ' . $r->getReasonPhrase(), PHP_EOL;
        echo $r->getBody(), PHP_EOL, PHP_EOL, PHP_EOL;
    }
    echo $e->getTraceAsString(), PHP_EOL;
}
```

请求成功后，你会获得一个 `GuzzleHttp\Psr7\Response` 的应答对象。
阅读 Guzzle 文档 [Using Response](https://docs.guzzlephp.org/en/stable/quickstart.html#using-responses) 进一步了解如何访问应答内的信息。

### 异步请求

使用客户端提供的 `getAsync`、`headAsync`、`putAsync`、`postAsync`、`patchAsync` 或 `deleteAsync` 方法发送异步请求。

```php
<?php

$promise = $instance
    ->chain('v3/refund/domestic/refunds')
    ->postAsync([
        'json' => [
            'transaction_id' => '1217752501201407033233368018',
            'out_refund_no'  => '1217752501201407033233368018',
            'amount'         => [
                'refund'   => 888,
                'total'    => 888,
                'currency' => 'CNY',
            ],
        ],
    ])
    ->then(static function ($response) {
        // 正常逻辑回调处理
        echo $response->getBody(), PHP_EOL;
        return $response;
    })
    ->otherwise(static function ($e) {
        // 异常错误处理
        echo $e->getMessage(), PHP_EOL;
        if ($e instanceof \GuzzleHttp\Exception\RequestException && $e->hasResponse()) {
            $r = $e->getResponse();
            echo $r->getStatusCode() . ' ' . $r->getReasonPhrase(), PHP_EOL;
            echo $r->getBody(), PHP_EOL, PHP_EOL, PHP_EOL;
        }
        echo $e->getTraceAsString(), PHP_EOL;
    })
    ->wait();// 同步等待
```

`[get|head|post|put|patch|delete]Async` 返回的是 [Guzzle Promises](https://github.com/guzzle/promises)。你可以做两件事：

+ 成功时使用 `then()` 处理得到的 `Psr\Http\Message\ResponseInterface`，（可选地）将它传给下一个 `then()`
+ 失败时使用 `otherwise()` 处理异常

最后使用 `wait()` 等待请求执行完成。

### 链式 URI Template

[URI Template](https://www.rfc-editor.org/rfc/rfc6570.html) 是表达 URI 中变量的一种方式。微信支付 API 使用这种方式表示
URL Path 中的单号或者 ID。

```
# 使用微信支付订单号查询订单
GET /v3/pay/transactions/id/{transaction_id}

# 使用商户订单号查询订单
GET /v3/pay/transactions/out-trade-no/{out_trade_no}
```

使用 [链式](https://en.wikipedia.org/wiki/Method_chaining) URI Template，你能像书写代码一样流畅地书写 URL，轻松地输入路径并传递
URL 参数。

链式串联的基本单元是 URI Path 中的 [segments](https://www.rfc-editor.org/rfc/rfc3986.html#section-3.3)，`segments`
之间以 `->` 连接。连接的规则如下：

+ 普通 segment
    + 直接书写。例如 `v3->pay->transactions->native`
    + 使用 `chain()`。例如 `chain('v3/pay/transactions/native')`
+ 包含连字号(-)的 segment
    + 使用驼峰 camelCase 风格书写。例如 `merchant-service` 可写成 `merchantService`
    + 使用 `{'foo-bar'}` 方式书写。例如 `{'merchant-service'}`
+ URL 中的 Path 变量应使用这种写法，避免自行组装或者使用 `chain()`，导致大小写处理错误
    + **推荐使用** `_variable_name_` 方式书写，支持 IDE 提示。例如 `v3->pay->transactions->id->_transaction_id_`。
    + 使用 `{'{variable_name}'}` 方式书写。例如 `v3->pay->transactions->id->{'{transaction_id}'}`
+ 请求的 `HTTP METHOD` 作为链式最后的执行方法。例如 `v3->pay->transactions->native->post([ ... ])`
+ Path 变量的值，以同名参数传入执行方法
+ Query 参数，以名为 `query` 的参数传入执行方法

以[查询订单](https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter3_4_2.shtml) `GET` 方法为例：

```php
<?php

$promise = $instance
    ->v3->pay->transactions->id->_transaction_id_
    ->getAsync([
        // Query 参数
        'query'          => ['mchid' => '1230000109'],
        // 变量名 => 变量值
        'transaction_id' => '1217752501201407033233368018',
    ]);
```

以 [关闭订单](https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter3_4_3.shtml) `POST` 方法为例：

```php
<?php

$promise = $instance
    ->v3->pay->transactions->outTradeNo->_out_trade_no_->close
    ->postAsync([
        // 请求消息
        'json'         => ['mchid' => '1230000109'],
        // 变量名 => 变量值
        'out_trade_no' => '1217752501201407033233368018',
    ]);
```

### 自定义扩展

使用自定义扩展处理器和中间件系统来发送HTTP请求。

#### 处理器

* 一个处理器函数接受一个 Psr\Http\Message\RequestInterface 和一个请求选项数组，并返回一个用
  Psr\Http\Message\ResponseInterface 填充的 GuzzleHttp\Promise\PromiseInterface，或被一个异常拒绝。
* 你可以使用客户端构造函数的 handler 选项为客户端提供一个自定义处理器。 重要的是要理解Guzzle使用的几个请求选项要求用特定的中间件来封装客户端要使用的处理器。
  你可以通过在 GuzzleHttp\HandlerStack::create(callable $handler = null) 静态方法中封装处理器来确保你提供给客户端的处理器会使用默认中间件。

#### 中间件

* 中间件通过在生成响应的过程中调用它们来增强处理器的功能。
* 中间件函数返回一个函数，该函数接受下一个要调用的处理器。 这个返回的函数接着返回另一个充当组合(composed)处理器的函数 --
  它接受一个请求和选项，并返回一个用响应填充的Promise。 你的组合的中间件可以修改请求、添加自定义请求选项，以及修改下游处理器返回的Promise。

#### 处理器堆栈

* 处理器堆栈表示一个要应用于基本处理器函数的中间件堆栈。 你可以将中间件推送(push)
  到堆栈以将其添加到堆栈的顶部，也可以将中间件卸载(unshift)到堆栈中以将其添加到堆栈的底部。
  堆栈被解析后，该处理器将被推送到堆栈。然后从堆栈中弹出(popped)每个值，并封装从堆栈中弹出的前一个值。

```php
<?php

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

// 假设集中管理服务器接入点为内网`http://192.168.169.170:8080/`地址，并提供两个URI供签名及验签
// - `/wechatpay-merchant-request-signature` 为请求签名
// - `/wechatpay-response-merchant-validation` 为响应验签
$client = new Client(['base_uri' => 'http://192.168.169.170:8080/']);

// 请求参数签名，返回字符串形如`\WeChatPay\Formatter::authorization`返回的字符串
$remoteSigner = function (RequestInterface $request) use ($client, $merchantId): string {
    return (string)$client->post('/wechatpay-merchant-request-signature', ['json' => [
        'mchid' => $merchantId,
        'verb'  => $request->getMethod(),
        'uri'   => $request->getRequestTarget(),
        'body'  => (string)$request->getBody(),
    ]])->getBody();
};

// 返回结果验签，返回可以是4xx,5xx，与远程验签应用约定返回字符串'OK'为验签通过
$remoteVerifier = function (ResponseInterface $response) use ($client, $merchantId): string {
    $nonce     = $response->getHeader('Wechatpay-Nonce');
    $serial    = $response->getHeader('Wechatpay-Serial');
    $signature = $response->getHeader('Wechatpay-Signature');
    $timestamp = $response->getHeader('Wechatpay-Timestamp');
    return (string)$client->post('/wechatpay-response-merchant-validation', ['json' => [
        'mchid'     => $merchantId,
        'nonce'     => $nonce,
        'serial'    => $serial,
        'signature' => $signature,
        'timestamp' => $timestamp,
        'body'      => (string)$response->getBody(),
    ]])->getBody();
};

$stack = $instance->getClient()->getConfig('handler');

// 卸载SDK内置签名中间件
$stack->remove('signer');

// 注册内网远程请求签名中间件
$stack->before('prepare_body', Middleware::mapRequest(
    static function (RequestInterface $request) use ($remoteSigner): RequestInterface {
        return $request->withHeader('Authorization', $remoteSigner($request));
    }
), 'signer');

// 卸载SDK内置验签中间件
$stack->remove('verifier');

// 注册内网远程请求验签中间件
$stack->before('http_errors', static function (callable $handler) use ($remoteVerifier): callable {
    return static function (RequestInterface $request, array $options = []) use ($remoteVerifier, $handler) {
        return $handler($request, $options)->then(
            static function (ResponseInterface $response) use ($remoteVerifier, $request): ResponseInterface {
                try {
                    if ($remoteVerifier($response) === 'OK') { // 远程验签约定，返回字符串`OK`作为验签通过
                        throw new RequestException('签名验签失败', $request, $response, $exception ?? null);
                    }
                } catch (\Throwable $exception) {
                    //
                }
                return $response;
            }
        );
    };
}, 'verifier');

// 链式/同步/异步请求APIv3即可，例如:
$instance->v3->certificates
    ->getAsync()
    ->then(static function ($res) {
        return $res->getBody();
    })
    ->wait();
```

## License

Nacosvel OpenHTTP is made available under the MIT License (MIT). Please see [License File](LICENSE) for more information.
