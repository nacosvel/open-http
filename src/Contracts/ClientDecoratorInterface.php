<?php

namespace Nacosvel\OpenHttp\Contracts;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

interface ClientDecoratorInterface
{
    public function getRequestClient(): ClientInterface;

    public function setRequestClient(ClientInterface $client): static;

    /**
     * Get a client configuration option.
     *
     * These options include default request options of the client, a "handler"
     * (if utilized by the concrete client), and a "base_uri" if utilized by
     * the concrete client.
     *
     * @param string|null $option The config option to retrieve.
     *
     * @return mixed
     */
    public function getRequestClientConfig(?string $option = null): mixed;

    /**
     * HTTP handler function to use with the stack.
     *
     * The returned handler will wrap the provided handler or use the most
     * appropriate default handler for your system. The returned HandlerStack has
     * support for cookies, redirects, HTTP error exceptions, and preparing a body
     * before sending.
     *
     * @return HandlerStack
     */
    public function getRequestClientHandler(): HandlerStack;

    /**
     * Request the remote $uri by a HTTP $method verb
     *
     * @param string $method  The uri string.
     * @param string $uri     The method string.
     * @param array  $options The options.
     *
     * @return ResponseInterface The `Psr\Http\Message\ResponseInterface` instance
     */
    public function request(string $method, string $uri = '', array $options = []): ResponseInterface;

    /**
     * Async request the remote $uri by a HTTP $method verb
     *
     * @param string $method  The uri string.
     * @param string $uri     The method string.
     * @param array  $options The options.
     *
     * @return PromiseInterface The `GuzzleHttp\Promise\PromiseInterface` instance
     */
    public function requestAsync(string $method, string $uri = '', array $options = []): PromiseInterface;
}
