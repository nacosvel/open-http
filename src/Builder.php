<?php

namespace Nacosvel\OpenHttp;

use ArrayIterator;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Nacosvel\OpenHttp\Contracts\ChainableInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Chainable the client for sending HTTP requests.
 */
final class Builder
{
    /**
     * Builder Decorator the chainable `GuzzleHttp\Client`
     *
     * @param array $options
     * @param array $config
     *
     * @return ChainableInterface
     * @example
     * ```
     * $instance = Builder::factory([]);
     * $response = $instance->chain('nacos/v2/cs/config')
     *      ->get(['namespaceId' => 'public']);
     * $response = $instance->chain('nacos/v2/ns/instance')
     *      ->post(['groupName' => 'DEFAULT_GROUP']);
     * $instance->nacos->v2->cs->config
     *      ->getAsync(['namespaceId' => 'public'])->wait();
     * $instance->nacos->v2->ns->instance
     *      ->postAsync(['groupName' => 'DEFAULT_GROUP'])
     *      ->then(static fn(ResponseInterface $response): PromiseInterface => {})
     *      ->otherwise(static fn(Exception $e): PromiseInterface => {})
     *      ->wait();
     * ```
     */
    public static function factory(array $options = [], array $config = []): ChainableInterface
    {
        return new Chainable([], new ClientDecorator($options), $config);
    }

    private function __construct()
    {

    }

}
