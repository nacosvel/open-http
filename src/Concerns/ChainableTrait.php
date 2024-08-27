<?php

namespace Nacosvel\OpenHttp\Concerns;

use GuzzleHttp\Promise\PromiseInterface;
use Nacosvel\OpenHttp\Contracts\ChainableInterface;
use Nacosvel\OpenHttp\Contracts\ClientDecoratorInterface;
use Psr\Http\Message\ResponseInterface;

trait ChainableTrait
{
    abstract public function getClient(): ClientDecoratorInterface;

    abstract public function setClient(ClientDecoratorInterface $instance): ChainableInterface;

    abstract public function getConfig(string $key = null): mixed;

    abstract public function setConfig(array $config): ChainableInterface;

    abstract public function pathname(string $separator = '/'): string;

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
    public function get(array $options = []): ResponseInterface
    {
        return $this->getClient()->request('GET', $this->pathname(), $options);
    }

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
    public function head(array $options = []): ResponseInterface
    {
        return $this->getClient()->request('HEAD', $this->pathname(), $options);
    }

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
    public function put(array $options = []): ResponseInterface
    {
        return $this->getClient()->request('PUT', $this->pathname(), $options);
    }

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
    public function post(array $options = []): ResponseInterface
    {
        return $this->getClient()->request('POST', $this->pathname(), $options);
    }

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
    public function patch(array $options = []): ResponseInterface
    {
        return $this->getClient()->request('PATCH', $this->pathname(), $options);
    }

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
    public function delete(array $options = []): ResponseInterface
    {
        return $this->getClient()->request('DELETE', $this->pathname(), $options);
    }

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
    public function request(string $method, array $options = []): ResponseInterface
    {
        return $this->getClient()->request($method, $this->pathname(), $options);
    }


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
    public function getAsync(array $options = []): PromiseInterface
    {
        return $this->getClient()->requestAsync('GET', $this->pathname(), $options);
    }

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
    public function headAsync(array $options = []): PromiseInterface
    {
        return $this->getClient()->requestAsync('HEAD', $this->pathname(), $options);
    }

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
    public function putAsync(array $options = []): PromiseInterface
    {
        return $this->getClient()->requestAsync('PUT', $this->pathname(), $options);
    }

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
    public function postAsync(array $options = []): PromiseInterface
    {
        return $this->getClient()->requestAsync('POST', $this->pathname(), $options);
    }

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
    public function patchAsync(array $options = []): PromiseInterface
    {
        return $this->getClient()->requestAsync('PATCH', $this->pathname(), $options);
    }

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
    public function deleteAsync(array $options = []): PromiseInterface
    {
        return $this->getClient()->requestAsync('DELETE', $this->pathname(), $options);
    }

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
    public function requestAsync(string $method, array $options = []): PromiseInterface
    {
        return $this->getClient()->requestAsync($method, $this->pathname(), $options);
    }

}
