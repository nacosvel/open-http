<?php

namespace Nacosvel\OpenHttp;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\UriTemplate\UriTemplate;
use Nacosvel\OpenHttp\Contracts\ClientDecoratorInterface;
use Nacosvel\OpenHttp\Middlewares\RetryMiddleware;
use Psr\Http\Message\ResponseInterface;

class ClientDecorator implements ClientDecoratorInterface
{
    protected ClientInterface $client;

    public function __construct(array $options = [])
    {
        $this->setRequestClient(new Client($options));
    }

    /**
     * @return ClientInterface
     */
    public function getRequestClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * @param ClientInterface $client
     *
     * @return static
     */
    public function setRequestClient(ClientInterface $client): static
    {
        if (false === is_null($client->getConfig('retry_max'))) {
            $handler = $client->getConfig('handler');
            $handler->remove('nacosvel.open_http.retry_request_middleware');
            $handler->push(new RetryMiddleware(), 'nacosvel.open_http.retry_request_middleware');
        }
        $this->client = $client;
        return $this;
    }

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
    public function getConfig(?string $option = null): mixed
    {
        return $this->getRequestClient()->getConfig($option);
    }

    /**
     * Create and send an HTTP request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param string $method  HTTP method.
     * @param string $uri     URI object or string.
     * @param array  $options Request options to apply.
     *
     * @return ResponseInterface The `Psr\Http\Message\ResponseInterface` instance
     * @throws GuzzleException
     */
    public function request(string $method, string $uri = '', array $options = []): ResponseInterface
    {
        return $this->getRequestClient()->request($method, UriTemplate::expand($uri, $options), $options);
    }

    /**
     * Create and send an asynchronous HTTP request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well. Use an array to provide a URL
     * template and additional variables to use in the URL template expansion.
     *
     * @param string $method  HTTP method
     * @param string $uri     URI object or string.
     * @param array  $options Request options to apply.
     *
     * @return PromiseInterface The `GuzzleHttp\Promise\PromiseInterface` instance
     */
    public function requestAsync(string $method, string $uri = '', array $options = []): PromiseInterface
    {
        return $this->getRequestClient()->requestAsync($method, UriTemplate::expand($uri, $options), $options);
    }
}
