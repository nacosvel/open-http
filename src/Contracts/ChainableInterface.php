<?php

namespace Nacosvel\OpenHttp\Contracts;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

interface ChainableInterface
{
    /**
     * @return ClientDecoratorInterface The `ClientDecorator` instance
     */
    public function getClient(): ClientDecoratorInterface;

    /**
     * @param ClientDecoratorInterface $instance The `ClientDecorator` instance
     *
     * @return ChainableInterface
     */
    public function setClient(ClientDecoratorInterface $instance): ChainableInterface;

    /**
     * Get ths `Chainable` config
     *
     * @param string|null $key
     *
     * @return array|mixed|null
     */
    public function getConfig(string $key = null): mixed;

    /**
     * Set ths `Chainable` config
     *
     * @param array $config The `Chainable` config
     *
     * @return ChainableInterface
     */
    public function setConfig(array $config): ChainableInterface;

    /**
     * Chainable the given $segments with the ChainableInterface instance
     *
     * @param string $segments  The segments or `URI`
     * @param string $separator The URI separator, default is slash(`/`) character
     *
     * @return ChainableInterface
     */
    public function chain(string $segments, string $separator = '/'): ChainableInterface;

    /**
     * Create and send an HTTP GET request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param array $options Request options to apply.
     *
     * @return ResponseInterface
     */
    public function get(array $options = []): ResponseInterface;

    /**
     * Create and send an HTTP HEAD request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param array $options Request options to apply.
     *
     * @return ResponseInterface
     */
    public function head(array $options = []): ResponseInterface;

    /**
     * Create and send an HTTP PUT request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param array $options Request options to apply.
     *
     * @return ResponseInterface
     */
    public function put(array $options = []): ResponseInterface;

    /**
     * Create and send an HTTP POST request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param array $options Request options to apply.
     *
     * @return ResponseInterface
     */
    public function post(array $options = []): ResponseInterface;

    /**
     * Create and send an HTTP PATCH request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param array $options Request options to apply.
     *
     * @return ResponseInterface
     */
    public function patch(array $options = []): ResponseInterface;

    /**
     * Create and send an HTTP DELETE request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param array $options Request options to apply.
     *
     * @return ResponseInterface
     */
    public function delete(array $options = []): ResponseInterface;

    /**
     * Create and send an HTTP request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param string $method  HTTP method.
     * @param array  $options Request options to apply.
     *
     * @return ResponseInterface
     */
    public function request(string $method, array $options = []): ResponseInterface;


    /**
     * Create and send an asynchronous HTTP GET request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well. Use an array to provide a URL
     * template and additional variables to use in the URL template expansion.
     *
     * @param array $options Request options to apply.
     *
     * @return PromiseInterface
     */
    public function getAsync(array $options = []): PromiseInterface;

    /**
     * Create and send an asynchronous HTTP HEAD request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well. Use an array to provide a URL
     * template and additional variables to use in the URL template expansion.
     *
     * @param array $options Request options to apply.
     *
     * @return PromiseInterface
     */
    public function headAsync(array $options = []): PromiseInterface;

    /**
     * Create and send an asynchronous HTTP PUT request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well. Use an array to provide a URL
     * template and additional variables to use in the URL template expansion.
     *
     * @param array $options Request options to apply.
     *
     * @return PromiseInterface
     */
    public function putAsync(array $options = []): PromiseInterface;

    /**
     * Create and send an asynchronous HTTP POST request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well. Use an array to provide a URL
     * template and additional variables to use in the URL template expansion.
     *
     * @param array $options Request options to apply.
     *
     * @return PromiseInterface
     */
    public function postAsync(array $options = []): PromiseInterface;

    /**
     * Create and send an asynchronous HTTP PATCH request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well. Use an array to provide a URL
     * template and additional variables to use in the URL template expansion.
     *
     * @param array $options Request options to apply.
     *
     * @return PromiseInterface
     */
    public function patchAsync(array $options = []): PromiseInterface;

    /**
     * Create and send an asynchronous HTTP DELETE request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well. Use an array to provide a URL
     * template and additional variables to use in the URL template expansion.
     *
     * @param array $options Request options to apply.
     *
     * @return PromiseInterface
     */
    public function deleteAsync(array $options = []): PromiseInterface;

    /**
     * Create and send an asynchronous HTTP request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well. Use an array to provide a URL
     * template and additional variables to use in the URL template expansion.
     *
     * @param string $method  HTTP method.
     * @param array  $options Request options to apply.
     *
     * @return PromiseInterface
     */
    public function requestAsync(string $method, array $options = []): PromiseInterface;

}
