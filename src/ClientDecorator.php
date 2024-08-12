<?php

namespace Nacosvel\OpenHttp;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\UriTemplate\UriTemplate;
use Nacosvel\OpenHttp\Contracts\ClientDecoratorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class ClientDecorator implements ClientDecoratorInterface
{
    protected Client $client;

    public function __construct(array $options = [])
    {
        $this->client = new Client($options);
    }

    /**
     * Create and send an HTTP request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param string              $method  HTTP method.
     * @param string|UriInterface $uri     URI object or string.
     * @param array               $options Request options to apply.
     *
     * @return ResponseInterface The `Psr\Http\Message\ResponseInterface` instance
     */
    public function request(string $method, $uri, array $options = []): ResponseInterface
    {
        return $this->client->request($method, UriTemplate::expand($uri, $options), $options);
    }

    /**
     * Create and send an asynchronous HTTP request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well. Use an array to provide a URL
     * template and additional variables to use in the URL template expansion.
     *
     * @param string              $method  HTTP method
     * @param string|UriInterface $uri     URI object or string.
     * @param array               $options Request options to apply.
     *
     * @return PromiseInterface The `GuzzleHttp\Promise\PromiseInterface` instance
     */
    public function requestAsync(string $method, $uri, array $options = []): PromiseInterface
    {
        return $this->client->requestAsync($method, UriTemplate::expand($uri, $options), $options);
    }
}
