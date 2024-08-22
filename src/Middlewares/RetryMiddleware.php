<?php

namespace Nacosvel\OpenHttp\Middlewares;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\RetryMiddleware as Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Middleware that retries requests based on the boolean result of
 * invoking the provided "decider" function.
 */
class RetryMiddleware
{
    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options = []) use ($handler): PromiseInterface {
            $middleware = new Middleware($this->decider($options), $handler, $this->delay($options));
            return $middleware($request, $options);
        };
    }

    /**
     * the provided "decider" function
     *
     * @param array $options
     *
     * @return (callable(int, RequestInterface, ResponseInterface, \Exception): bool) Function that accepts the number of retries, a request, [response], and [exception] and returns true if the request is to be retried.
     */
    protected function decider(array $options = []): callable
    {
        return static function (int $retries, RequestInterface $request, ResponseInterface $response = null, $exception = null) use ($options): bool {
            if (false === array_key_exists('retry_max', $options)) {
                return false;
            }

            $is_callable_decider = array_key_exists('retry_decider', $options) && is_callable($options['retry_decider']);
            if ($is_callable_decider) {
                return call_user_func($options['retry_decider'], $options, $retries, $request, $response, $exception);
            }

            if ($retries >= $options['retry_max']) {
                return false;
            }

            $matches = function (string $pattern, string $subject): bool {
                $pattern = str_ireplace('x', '*', $pattern);
                $pattern = str_replace('\*', '.*', preg_quote($pattern, '/'));
                return preg_match("/^{$pattern}$/", $subject) === 1;
            };

            // 如果响应状态码为 5xx，则重试
            if ($response instanceof ResponseInterface) {
                foreach ($options['retry_status'] ?? ['5xx'] as $status) {
                    if ($matches($status, $response->getStatusCode())) {
                        return true;
                    }
                }
            }

            // 如果抛出了请求异常，则重试
            foreach ($options['retry_exceptions'] ?? [RequestException::class] as $interface) {
                if ($exception instanceof $interface) {
                    return true;
                }
            }

            return false;
        };
    }

    /**
     * the provided "delay" function
     *
     * @param array $options
     *
     * @return (callable(int): int) Function that accepts the number of retries and returns the number of milliseconds to delay.
     */
    protected function delay(array $options = []): callable
    {
        return static function (int $retries) use ($options): int {
            $is_callable_delay = array_key_exists('retry_delay', $options) && is_callable($options['retry_delay']);
            if ($is_callable_delay) {
                return call_user_func($options['retry_delay'], $options, $retries);
            }
            return 2 ** ($retries - 1) * 1000;
        };
    }

}
