<?php

namespace Nacosvel\OpenHttp\Contracts;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

interface ClientDecoratorInterface
{
    /**
     * Request the remote $uri by a HTTP $method verb
     *
     * @param string $method  The uri string.
     * @param string $uri     The method string.
     * @param array  $options The options.
     *
     * @return ResponseInterface The `Psr\Http\Message\ResponseInterface` instance
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface;

    /**
     * Async request the remote $uri by a HTTP $method verb
     *
     * @param string $method  The uri string.
     * @param string $uri     The method string.
     * @param array  $options The options.
     *
     * @return PromiseInterface The `GuzzleHttp\Promise\PromiseInterface` instance
     */
    public function requestAsync(string $method, string $uri, array $options = []): PromiseInterface;

}
